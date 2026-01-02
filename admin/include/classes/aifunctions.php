<?php

/**
 * @Copyright Copyright (C) 2009-2010 Ahmad Bilal
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * Company:     Buruj Solutions
 + Contact:     https://www.burujsolutions.com , info@burujsolutions.com
 * Created on:  Nov 22, 2010
 ^
 + Project:     JS Jobs
 ^
 */

defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Factory;

class aifunctions {

    // 1 is for front end
    // 2 is for admin (in admin case publish status not in considration ??) ??
    function aiJobFeaturesMajorQuery($aijobsearch,$query_for = 1){
        $db = Factory::getDbo();
        $job_ids_list = [];
        $curdate = Factory::getDate()->format('Y-m-d');
        $cache = Factory::getCache('com_jsjobs', '');
        if (empty($job_ids_list) && !empty($aijobsearch)) {
            $aijobsearch = $db->escape(trim($aijobsearch));
            $words = array_filter(explode(' ', $aijobsearch));
            $wordCount = count($words);

            $query_parts = [];

            $publish_check_query = '';
            if($query_for == 1){ // front end case (check if job is published)
                $publish_check_query = " AND DATE(job.startpublishing) <= '".$curdate."'
                                         AND DATE(job.stoppublishing) >= '".$curdate."' ";
            }

            if ($wordCount < 4) {
                $query_parts[] = "SELECT job.id AS id,job.id AS jobid, job.title, 999 AS score FROM #__js_job_jobs AS job
                    WHERE job.status = 1
                    ".$publish_check_query."
                    AND job.aijobsearchtext LIKE '%$aijobsearch%'";
            }

            $query_parts[] = "SELECT job.id AS id,job.id AS jobid, job.title,
                MATCH (job.aijobsearchtext) AGAINST ('".$aijobsearch."' IN NATURAL LANGUAGE MODE) AS score
                FROM #__js_job_jobs AS job
                WHERE job.status = 1
                ".$publish_check_query."
                AND MATCH (job.aijobsearchtext) AGAINST ('".$aijobsearch."' IN NATURAL LANGUAGE MODE)";

            $query_parts[] = "SELECT job.id AS id,job.id AS jobid, job.title,
                MATCH (job.aijobsearchdescription) AGAINST ('".$aijobsearch."' IN NATURAL LANGUAGE MODE) AS score
                FROM #__js_job_jobs AS job
                WHERE job.status = 1
                ".$publish_check_query."
                AND MATCH (job.aijobsearchdescription) AGAINST ('".$aijobsearch."' IN NATURAL LANGUAGE MODE)";

            $union_query = implode(" UNION ", $query_parts);


            $db->setQuery($union_query);
            $results = $db->loadObjectList();

            $highest_score = 0;
            $id_list = [];

            foreach ($results as &$result) {
                $custom_score = 0;

                if (strtolower($aijobsearch) === strtolower(trim($result->title))) {
                    $custom_score += ($wordCount * 10) + 10;
                    $result->score = 0;
                } elseif ($result->score == 999) {
                    $custom_score += ($wordCount * 10) + 8;
                    $result->score = 0;
                } else {
                    for ($i = 0; $i < $wordCount - 1; $i++) {
                        $combo = $words[$i] . ' ' . ($words[$i + 1] ?? '');
                        if (stripos($result->title, $combo) !== false) {
                            $custom_score += 10;
                        }
                    }
                }

                $result->custom_score = $custom_score;
                $highest_score = max($highest_score, $result->score);
            }

            usort($results, function ($a, $b) {
                return ($b->custom_score === $a->custom_score)
                    ? $b->score <=> $a->score
                    : $b->custom_score <=> $a->custom_score;
            });

            $results = $this->applyThresholdOnResults($results, $highest_score,1);

            foreach ($results as $job) {
                $id_list[] = $job->id;
            }

            //$unique_id = $this->getUniqueIdForCache();
            if (!empty($id_list)) {
                //$cache->store(implode(',', $id_list), 'ai_suggested_jobs_list_' . $unique_id);
                $job_ids_list = implode(',', $id_list);
            }
        }
        return $job_ids_list;
    }


    function getUniqueIdForCache() {
        $user = Factory::getUser();
        if ($user->id > 0) {
            $cache_id = 'user_' . $user->id;
        } else {
            $ip = !empty($_SERVER['REMOTE_ADDR']) ? filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP) : '0.0.0.0';
            $useragent = !empty($_SERVER['HTTP_USER_AGENT']) ? htmlspecialchars($_SERVER['HTTP_USER_AGENT'], ENT_QUOTES, 'UTF-8') : 'unknown';
            $cache_id = $ip . '_' . $useragent;
        }
        return base64_encode($cache_id);
    }

    function getRecordIdsForCurrentPage($job_ids_list, $page_num = 1) {
        $current_records_to_show = '';

        if (!empty($job_ids_list)) {
            $job_id_array = array_map('intval', explode(',', $job_ids_list));
            $pagination = $this->getjobsPagination();
            $limit = $pagination['limit'];
            $pagination_size = (int) $limit;

            $offset = ($page_num - 1) * $pagination_size;
            $current_records_array = array_slice($job_id_array, $offset, $pagination_size);

            if (!empty($current_records_array)) {
                $current_records_to_show = implode(',', $current_records_array);
            }
        }

        return $current_records_to_show;
    }

    function storeAIRecordsIDListCache($job_ids_list, $cache_for) {
        $cache_key_prefix = $this->getCacheKeyPrefix($cache_for);
        $unique_id = $this->getUniqueIdForCache();

        if (!empty($job_ids_list) && !empty($unique_id)) {
            $cache = Factory::getCache('com_jsjobs', '');
            $cache->store($job_ids_list, $cache_key_prefix . $unique_id);
        }
    }

    function getAIRecordsIDListFromCache($cache_for) {
        $cache_key_prefix = $this->getCacheKeyPrefix($cache_for);
        $unique_id = $this->getUniqueIdForCache();
        $result_list = '';

        if (!empty($unique_id)) {
            $cache = Factory::getCache('com_jsjobs', '');
            $result = $cache->get($cache_key_prefix . $unique_id);

            if ($result !== false) {
                $result_list = $result;
            }
        }

        return $result_list;
    }

    function getCacheKeyPrefix($type) {
        switch ((int)$type) {
            case 1:
                return 'ai_suggested_jobs_list_';
            case 2:
                return 'ai_suggested_jobs_dashboard_';
            case 3:
                return 'ai_websearch_jobs_list_';
            case 4:
                return 'ai_suggested_resume_list_';
            case 5:
                return 'ai_suggested_resume_dashboard_';
            case 6:
                return 'ai_websearch_resume_list_';
            default:
                return 'ai_suggested_jobs_list_';
        }
    }

    function applyThresholdOnResults($results, $highest_score, $enitity_for) {
        if (empty($results)) {
            return $results; // Return early if no results
        }

        $threshold = 30; // Percentage threshold
        $highest_custom_score = $results[0]->custom_score ?? 0;

        // Calculate threshold values
        $custom_score_threshold_value = ($threshold / 100) * $highest_custom_score;
        $score_threshold_value = ($threshold / 100) * $highest_score;

        // Track highest scores for each jobid
        $unique_results = [];

        foreach ($results as $result) {
            // Skip results below the threshold (except the first result)
            if (
                ($result->custom_score <= $custom_score_threshold_value && $result !== $results[0]) &&
                ($result->score <= $score_threshold_value && $result !== $results[0])
            ) {
                continue;
            }

            if($result->custom_score == 0 && $result->score < 1.5) continue;
            // Ensure uniqueness by entitiy id, keeping the highest custom_score and then the highest score
            if($enitity_for == 1){
                $record_id = $result->jobid;
            }else{
                $record_id = $result->resumeid;
            }

            if (
                !isset($unique_results[$record_id]) ||
                $result->custom_score > $unique_results[$record_id]->custom_score ||
                ($result->custom_score === $unique_results[$record_id]->custom_score && $result->score > $unique_results[$record_id]->score)
            ) {
                $unique_results[$record_id] = $result;
            }

            if (!isset($unique_results[$record_id]) || $result->score > $unique_results[$record_id]->score) {
                $unique_results[$record_id] = $result;
            }
        }
        return array_values($unique_results); // Return reindexed array
    }

    function getResumeByAIString($airesumesearch){
        if (empty($airesumesearch)) {
            return false;
        }

        $db = Factory::getDbo();
        $airesumesearch = $db->escape(trim($airesumesearch));
        $words = array_filter(explode(' ', $airesumesearch));
        $wordCount = count($words);

        $unionParts = [];

        if ($wordCount < 4) {
            $unionParts[] = "(
                SELECT DISTINCT resume.id, CONCAT(resume.alias,'-',resume.id) AS resumealiasid,
                    resume.first_name, resume.last_name, resume.application_title AS applicationtitle,
                    resume.id AS resumeid, '0' AS custom_score, '999' AS score
                FROM #__js_job_resume AS resume
                WHERE resume.status = 1
                AND resume.airesumesearchtext LIKE '%".$airesumesearch."%'
                ORDER BY score DESC
            )";
        }

        $unionParts[] = "(
            SELECT DISTINCT resume.id, CONCAT(resume.alias,'-',resume.id) AS resumealiasid,
                resume.first_name, resume.last_name, resume.application_title AS applicationtitle,
                resume.id AS resumeid, '0' AS custom_score,
                MATCH (resume.airesumesearchtext) AGAINST ('".$airesumesearch."' IN NATURAL LANGUAGE MODE) AS score
            FROM #__js_job_resume AS resume
            WHERE resume.status = 1
            AND MATCH (resume.airesumesearchtext) AGAINST ('".$airesumesearch."' IN NATURAL LANGUAGE MODE)
            ORDER BY score DESC
        )";

        $unionParts[] = "(
            SELECT DISTINCT resume.id, CONCAT(resume.alias,'-',resume.id) AS resumealiasid,
                resume.first_name, resume.last_name, resume.application_title AS applicationtitle,
                resume.id AS resumeid, '0' AS custom_score,
                MATCH (resume.airesumesearchdescription) AGAINST ('".$airesumesearch."' IN NATURAL LANGUAGE MODE) AS score
            FROM #__js_job_resume AS resume
            WHERE resume.status = 1
            AND MATCH (resume.airesumesearchdescription) AGAINST ('".$airesumesearch."' IN NATURAL LANGUAGE MODE)
            ORDER BY score DESC
        )";

        // echo '<br/>';
        // echo '<br/>';
        // echo var_dump($airesumesearch);
        // echo '<br/>';
        // echo '<br/>';
        // echo '<br/>';

        $query = implode(' UNION ', $unionParts);
        $db->setQuery($query);
        //echo var_dump($query);
        $results = $db->loadObjectList();

        // echo '<pre>'; print_r($results); echo '</pre>';

        $highest_score = 0;

        foreach ($results as &$result) {
            $custom_score = 0;

            if (strtolower($airesumesearch) === strtolower(trim($result->applicationtitle))) {
                $custom_score += ($wordCount * 10) + 8;
                $result->score = 0;
            } elseif ($result->score == 999) {
                $custom_score += ($wordCount * 10) + 6;
                $result->score = 0;
            } elseif ($result->score == 888) {
                $custom_score += ($wordCount * 10) + 4;
                $result->score = 0;
            } else {
                for ($i = 0; $i < $wordCount - 1; $i++) {
                    $combo = $words[$i] . ' ' . ($words[$i + 1] ?? '');
                    if (stripos($result->applicationtitle, $combo) !== false) {
                        $custom_score += 10;
                    }
                }
            }

            $result->custom_score = $custom_score;
            $highest_score = max($highest_score, $result->score);
        }
        unset($result);

        usort($results, function ($a, $b) {
            return ($b->custom_score === $a->custom_score) ? $b->score <=> $a->score : $b->custom_score <=> $a->custom_score;
        });

        return $this->applyThresholdOnResults($results, $highest_score, 2);
    }

    function getResumeBySearchAIString($airesumesearch, $limit, $limitstart){
        $app = Factory::getApplication();
        $input = $app->input;
        $cache = Factory::getCache('com_jsjobs', '');

        $pagenum = ($limit > 0) ? floor($limitstart / $limit) + 1 : 1;

        $resume_ids_list = '';

        $unique_id = getAIFunctionsClass()->getUniqueIdForCache();

        if ($pagenum > 1) {
            $resume_ids_list = $cache->get('ai_websearch_resume_list_' . $unique_id);
        }

        if (empty($resume_ids_list) && !empty($airesumesearch)) {
            $results = $this->getResumeByAIString($airesumesearch);
            if (!empty($results)) {
                $ids = array_column($results, 'id');
                $resume_ids_list = implode(',', $ids);
                $cache->store($resume_ids_list, 'ai_websearch_resume_list_' . $unique_id);
            }
        }

        $return_id_list = '';
        $total = 0;

        if (!empty($resume_ids_list)) {
            $ids = array_map('intval', explode(',', $resume_ids_list));
            $total = count($ids);

            // $limit = $this->getResumePaginationLimit();
            // $offset = ($pagenum - 1) * $limit;
            $ids_to_show = array_slice($ids, $limitstart, $limit);

            if (!empty($ids_to_show)) {
                // handle this fucntion
                //$data = $this->getJSModel('resume')->getResumesByResumeIds(implode(',', $ids_to_show));
                $return_id_list = implode(',', $ids_to_show);
            }
        }

        return [
            'total' => $total,
            'resume_ids' => $return_id_list
        ];
    }

    function getSuggestedResumesListing($job_id, $limit, $limitstart){
        $user = Factory::getUser();

        // handle this some other way ??
        // if (!$this->getJSModel('user')->isEmployer($user->id)) {
        //     return false;
        // }

        $app = Factory::getApplication();
        $pagenum = ($limit > 0) ? floor($limitstart / $limit) + 1 : 1;

        $cache = Factory::getCache('com_jsjobs', '');
        $unique_id = $this->getUniqueIdForCache();
        $resume_ids_list = '';

        if ($pagenum > 1) {
            $resume_ids_list = $cache->get('ai_suggested_resume_list_' . $unique_id);
        }

        if (empty($resume_ids_list)) {
            $job_ai_string = $this->getJobAIStringByJobId($job_id);

            if (!empty($job_ai_string)) {
                $results = $this->getResumeByAIString($job_ai_string);
                $id_list = [];

                if (!empty($results)) {
                    foreach ($results as $resume) {
                        $id_list[] = (int) $resume->id;
                    }
                }

                if (!empty($id_list)) {
                    $resume_ids_list = implode(',', $id_list);
                    $cache->store($resume_ids_list, 'ai_suggested_resume_list_' . $unique_id);
                }
            }
        }

        $data = [];
        $data['resume_ids'] = '';
        $data['total'] = 0;
        if (!empty($resume_ids_list)) {
            $ids_array = explode(',', $resume_ids_list);
            $total = count($ids_array);

            $ids_to_show = array_slice($ids_array, ($pagenum - 1) * 10, $limit); // Adjust 10 as needed

            $ids_to_show = implode(',',$ids_to_show);

            $data['resume_ids'] = $ids_to_show;
            $data['total'] = $total;
            // if (!empty($ids_to_show)) {
            //     $data = $this->getJSModel('resume')->getResumesByResumeIds($ids_to_show);
            // }
        }
        return $data;
    }


    function getJobAIStringByJobId($jobid){
        $db = Factory::getDbo();
        $job_ai_string = '';

        $query = "SELECT job.title, cat.cat_title, jobtype.title AS jobtypetitle
                  FROM #__js_job_jobs AS job
                  LEFT JOIN #__js_job_categories AS cat ON cat.id = job.jobcategory
                  LEFT JOIN #__js_job_jobtypes AS jobtype ON jobtype.id = job.jobtype
                  WHERE job.id = " . (int) $jobid;

        $db->setQuery($query);
        $job = $db->loadObject();

        if (!empty($job)) {
            $jobtitle   = $job->title;
            $jobcategory = $job->cat_title;
            $jobtype    = $job->jobtypetitle;

            if ($jobtitle != '') {
                $job_ai_string .= $jobtitle;
            }
            if ($jobcategory != '') {
                $job_ai_string .= ' ' . $jobcategory;
            }
            if ($jobtype != '') {
                $job_ai_string .= ' ' . $jobtype;
            }

            $job_ai_string = trim($job_ai_string);
        }

        return $job_ai_string;
    }


}
?>