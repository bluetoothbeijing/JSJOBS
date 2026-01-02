
            function draw() {
                var objects = document.getElementsByClassName('canvas_color_bg');
                for (var i = 0; i < objects.length; i++){
                    var canvas = objects[i];
                    if (canvas.getContext){
                      var ctx = canvas.getContext('2d');
                      ctx.fillStyle = "#F5F5F5";
                      ctx.beginPath();
                      ctx.moveTo(0,0);
                      ctx.lineTo(10,10);
                      ctx.lineTo(0,20);
                      ctx.fill();
                    }
                }
                var objects = document.getElementsByClassName('canvas_white_bg');
                for (var i = 0; i < objects.length; i++){
                    var canvas = objects[i];
                    if (canvas.getContext){
                      var ctx = canvas.getContext('2d');
                      ctx.fillStyle = "#FFFFFF";
                      ctx.beginPath();
                      ctx.moveTo(0,0);
                      ctx.lineTo(10,10);
                      ctx.lineTo(0,20);
                      ctx.fill();
                    }
                }
            }
            window.onload = function(){
                draw();
            }