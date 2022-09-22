"use strict";   //forces declaration of the scope of names

//Game is a constructor for a game board. 
//A game has a rectangular arrary of crateweights and a location for the bobcat that pushes them around.
//            it has methods move(dir)  to move the bobcat around,
//                            toString to show the gameboard as formatted text

//this version requires execution in an html file containing a div with id "myText" for displaying 
let Game = function (sd) {  //THIS IS A CONSTRUCTOR FOR A GAME OBJECT it must be called us new
    //private game data

    Util.seed = sd;

    let n = parseInt(Util.random() * 5) + 8;     //number of rows and columns    
    console.log("n = ");
    let b = [];      //initb (below) initializes to random weights and n x n
    let bob = {};    //the bob has a row,column, and direction (use 0=North,1=E,2=S,3=W)
    let target = {};
    let score = 0;
    let goal = {};
    let gameEnd = false;
    let dr = [-1, 0, 1, 0];    //nesw
    let dc = [0, 1, 0, -1];
    bob.r = n - 1;
    bob.c = parseInt(Util.random() * n);
    bob.d = 0;
    goal.r = bob.r;
    goal.c = bob.c;
    target.r = parseInt(Util.random() * (n - 2)) + 1;
    target.c = parseInt(Util.random() * (n - 2)) + 1;
    let wtprob = [.5, .75, .875];
    for (let r = 0; r < n; r++) {
        b[r] = [];
        for (let c = 0; c < n; c++) {
            let rnd = Util.random();
            if (rnd < wtprob[0])
                b[r][c] = 0;
            else if (rnd < wtprob[1])
                b[r][c] = 1;
            else if (rnd < wtprob[2])
                b[r][c] = 2;
            else
                b[r][c] = 3;
        }
    }
    while (b[target.r][target.c] == 0) {
        target.r = parseInt(Util.random() * (n - 2)) + 1;
        target.c = parseInt(Util.random() * (n - 2)) + 1;
    }
    b[target.r][target.c] = b[target.r][target.c] + 5;     //initializes target crate with weight 5+ original
    console.log(b[target.r][target.c]);
    b[bob.r][bob.c] = 0;            //bob cannot start ontop of a crate

    // ===========  public methods  =================
    //Game.prototype.move = function (dirch){   is an alternative approach for a public method

    this.returnScore = function () {
        return score;
    }

    let inBounds = function (nextR, nextC) {
        return 0 <= nextR && nextR < n && 0 <= nextC && nextC < n;
    }

    let slide = function (d) {
        let allowedWt = 4;
        let i, rr, cc;
        let wt = 0;
        for (i = 1; true; i++) {

            rr = bob.r + i * dr[d];
            cc = bob.c + i * dc[d];
            if (!inBounds(rr, cc)) {
                return false;
            }
            let tempW = b[rr][cc];
            if (b[rr][cc] >= 5) {
                tempW = b[rr][cc] - 5;
            }
            if (wt + tempW >= allowedWt) {
                return false;
            }
            if (b[rr][cc] === 0)
                break;
            wt = wt + tempW;
        }
        for (let j = 0; j < i; j++) {   //slide the right number of  times
            let rprev = rr - dr[d];
            let cprev = cc - dc[d];
            b[rr][cc] = b[rprev][cprev];
            rr = rprev;
            cc = cprev;
        }
        return true;
    }
    this.move = function (dirch) {
        if (!gameEnd) {
            //dirch is converted to  0123 meaning NESW 
            let d = -1;
            if (dirch === 87)
                d = 0;   //w
            if (dirch === 65)
                d = 3;   //a
            if (dirch === 83)
                d = 2;   //s
            if (dirch === 68)
                d = 1;   //d
            if (dirch === 32)
                d = bob.d;
            if (d < 0)
                return;       //ignore bad keys
            let nextR = bob.r + dr[d];
            let nextC = bob.c + dc[d];

            if (dirch === 32) {
                if (inBounds(nextR, nextC)) {
                    if (b[nextR][nextC] != 0) {
                        if (b[nextR][nextC] < 5) {
                            b[nextR][nextC] = 4;
                            setTimeout(function(){
                                b[nextR][nextC] = 0;
                            },1);                            
                            score = score + 100;
                        }
                    }
                }
            } else if (d === bob.d) {
                if (inBounds(nextR, nextC) && slide(d)) {
                    score++;
                    bob.r += dr[d];
                    bob.c += dc[d];
                    if (b[goal.r][goal.c] > 3) {
                        setTimeout(function () {
                            alert("You Win");
                        }, 10);
                        gameEnd = true;
                    }
                }

            } else if (d !== (bob.d + 2) % 4) {
                bob.d = d;

            }

            console.log("down");
        } else {
            console.log("game has ended");
        }
    }


    Game.prototype.toString = function () {  //public method of Game
        let out = "";
        for (let r = 0; r < n; r++) {
            for (let c = 0; c < n; c++) {
                if (r === bob.r && c === bob.c) {
                    if (bob.d === 0)
                        out = out + "^";
                    else if (bob.d === 1)
                        out = out + ">";
                    else if (bob.d === 2)
                        out = out + "v";
                    else
                        out = out + "<";
                } else
                    out = out + b[r][c];
            }
            out = out + "\n";
        }
        return "<pre>" + out + "</pre>";   //avoid browser formatting
    };

    Game.prototype.drawCanvas = function (ctx, imgs, dirch) {
        var dmnsn = 552;
        let temp = dmnsn / n;
        //background
        for (let r = 0; r < n; r++) {
            for (let c = 0; c < n; c++) {
                ctx.fillStyle = ((r + c) % 2 === 0) ? "#b9bbbd" : "#999b9e";
                if (r === goal.r && c === goal.c) {
                    ctx.fillStyle = "#40ff00";
                }
                let rOffset = c * temp;
                let cOffset = r * temp;
                ctx.fillRect(rOffset, cOffset, temp, temp);

                if (r === bob.r && c === bob.c) {

                    if (bob.d === 0)
                        ctx.drawImage(imgs["back"], rOffset + n / (n / 4), cOffset + n / (n / 4), temp - (temp / 8), temp - (temp / 8));
                    else if (bob.d === 1)
                        ctx.drawImage(imgs["right"], rOffset + n / (n / 4), cOffset + n / (n / 4), temp - (temp / 8), temp - (temp / 8));
                    else if (bob.d === 2)
                        ctx.drawImage(imgs["front"], rOffset + n / (n / 4), cOffset + n / (n / 4), temp - (temp / 8), temp - (temp / 8));
                    else
                        ctx.drawImage(imgs["left"], rOffset + n / (n / 4), cOffset + n / (n / 4), temp - (temp / 8), temp - (temp / 8));
                }else if (b[r][c] == 1) {
                    ctx.drawImage(imgs["crate"], rOffset + n / (n / 4), cOffset + n / (n / 4), temp - (temp / 8), temp - (temp / 8));
                }else if (b[r][c] == 2) {
                    ctx.drawImage(imgs["crate2"], rOffset + n / (n / 4), cOffset + n / (n / 4), temp - (temp / 8), temp - (temp / 8));
                }else if (b[r][c] == 3) {
                    ctx.drawImage(imgs["box"], rOffset + n / (n / 4), cOffset + n / (n / 4), temp - (temp / 8), temp - (temp / 8));
                }else if(b[r][c] == 4){
                     ctx.drawImage(imgs["expl"], rOffset + n / (n / 4), cOffset + n / (n / 4), temp - (temp / 8), temp - (temp / 8));
                }else if (b[r][c] >= 5) {
                    if (b[r][c] == 5) {
                        ctx.drawImage(imgs["goal"], rOffset + n / (n / 4), cOffset + n / (n / 4), temp - (temp / 8), temp - (temp / 8));
                    } else if (b[r][c] == 6) {
                        ctx.drawImage(imgs["goal1"], rOffset + n / (n / 4), cOffset + n / (n / 4), temp - (temp / 8), temp - (temp / 8));
                    } else if (b[r][c] == 7) {
                        ctx.drawImage(imgs["goal2"], rOffset + n / (n / 4), cOffset + n / (n / 4), temp - (temp / 8), temp - (temp / 8));
                    } else if (b[r][c] == 8) {
                        ctx.drawImage(imgs["goal3"], rOffset + n / (n / 4), cOffset + n / (n / 4), temp - (temp / 8), temp - (temp / 8));
                    }

                }
            }
        }

    }


};       //=========== end Game ================


//=======================================================================
let viewText = function (brd) {   //show in existing div myText    
    let inner = "<h1>" + brd + "</h1>";
    //document.getElementById("myText").innerHTML = inner; 
    document.getElementById("score").innerHTML = "Score: " + brd.returnScore();
};

    var canvas = document.getElementById("canvas");
    var ctx = canvas.getContext("2d");
    var img = document.getElementById("crate");
    var back = document.getElementById("back");
    var left = document.getElementById("left");
    var right = document.getElementById("right");
    var front = document.getElementById("front");
    var crate2 = document.getElementById("crate2");
    var box = document.getElementById("box");
    var goal = document.getElementById("goal");
    var goal1 = document.getElementById("goal1");
    var goal2 = document.getElementById("goal2");
    var goal3 = document.getElementById("goal3");
    var expl =  document.getElementById("expl");
    var imgs = {back, left, right, front, "crate": img, crate2, box, goal, goal1, goal2, goal3, expl};


window.onload = function () {     //called when the window has finished loading
    console.log("onload");
    let brd = new Game(seed);       //random seeding
    document.onkeydown = function (ev) {  //keydown event  
        let key = ev.keyCode;
        brd.move(key);
        viewText(brd);
        brd.drawCanvas(ctx, imgs, key);
    }
    viewText(brd);
    brd.drawCanvas(ctx, imgs);
};

document.getElementById("rstrt").onclick = function () {
    console.log("restart");
    let brd = new Game(seed); 
    document.onkeydown = function (ev) {  //keydown event  
        console.log("down ");
        let key = ev.keyCode;
        brd.move(key);
        viewText(brd);
        brd.drawCanvas(ctx, imgs);
    };
    viewText(brd);
    brd.drawCanvas(ctx, imgs);
};


document.getElementById("rndm").onclick = function () {
    seed = Util.random()*998888 + 1111;
    console.log(seed);
    let brd = new Game(seed);
    document.onkeydown = function (ev) {  //keydown event  
        console.log("down ");
        let key = ev.keyCode;
        brd.move(key);
        viewText(brd);
        brd.drawCanvas(ctx, imgs);
    };
    viewText(brd);
    brd.drawCanvas(ctx, imgs);
}


document.querySelectorAll("button").forEach(function (item) {
    item.addEventListener('focus', function () {
        this.blur();
    })
})

window.onkeydown = function (e) {
    return !(e.keyCode == 32 && e.target == document.body);
};
