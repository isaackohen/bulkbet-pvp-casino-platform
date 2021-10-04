var requestify = require('requestify');

var double = function(io, domain) {
    // base settings
    this.io = io;
    this.domain = domain;
    
    // timer
    this.timer = false;
    this.time = 0;

    // circle
    this.rotate = 0;
    this.rotate_time = 0;
}

double.prototype.log = function(log) {
    console.log('[DOUBLE] ' + log);
}

double.prototype.start = function() {
    this.post('/api/roulette/getGame', {}, (err, res) => {
        if(err) return;
        this.id = res.id;
        if(res.status == 0) this.log('Cтатус игры #' + this.id + ' : ' + res.status);
        if(res.status == 1) return this.startTimer(res.time);
        if(res.status == 2) return this.showSlider();
        if(res.status == 3) return this.newGame();
    });
}

double.prototype.showSlider = function() {
    this.updateStatus(2); // show slider status
    this.timer = false; // enable timer
    this.post('/api/roulette/getSlider', {}, (err, res) => {
        if(err) return;
        // emit slider
        this.updateStatus(3); // new game status
        this.log('Показываем слайдер! (' + res.color + '/' + res.number + ')');
        setTimeout(() => {
            this.newGame();
        }, res.time);
    });
}

double.prototype.newGame = function() {
    this.post('/api/roulette/newGame', {}, (err, res) => {
        if(err) return;
        // emit new game
        this.id = res.id;
        this.log('Новая игра #' + this.id);
    });
}

double.prototype.startTimer = function(time) {
    if(this.timer) return this.log('Таймер уже запущен!'); // fix 
    this.timer = true; // disable timer
    this.updateStatus(1); // set timer status
    this.time = time-1; // important
    /*this.log('Таймер : ' + this.time + ' сек');*/
    this.socketEmit({
        type : 'timer',
        time : this.time
    });
    // emit timer
    this.timerInterval = setInterval(() => {
        if(this.time <= 0)
        {
            this.log('Таймер закончился!');
            this.socketEmit({
                type : 0,
                time : this.time
            });
            clearInterval(this.timerInterval);
            this.showSlider();
            return; // important
        }
        this.time--;

        /*this.log('Таймер : ' + this.time + ' сек');*/
        
        this.socketEmit({
            type : 'timer',
            time : this.time
        });
    }, 1000);
}

double.prototype.updateStatus = function(status) {
    this.post('/api/roulette/updateStatus', {
        status : status
    }, (err, res) => {
        if(err) return;
        if(res.success) this.log('Статус игры #' + this.id + ' изменен на ' + status);
    });
}

double.prototype.post = function(url, data, done)
{
    requestify.post(this.domain + url, data)
    .then((res) => {
        return done(false, JSON.parse(res.body));
    }, (err) => {
        this.log('Error with request ' + url);
        return done(true, null);
    });
}

double.prototype.socketEmit = function(array)
{
    return this.io.sockets.emit('roulette', array);
}

// double.prototype

module.exports = double;