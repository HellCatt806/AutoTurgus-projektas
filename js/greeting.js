window.onload = function() {
    setTimeout(function() {
        let greeting = document.getElementById('greeting');
        greeting.style.transition = 'opacity 1s ease-out';
        greeting.style.opacity = '0';

        setTimeout(function() {
            greeting.remove(); //removes greeting
        }, 1000);
    }, 1500);
};