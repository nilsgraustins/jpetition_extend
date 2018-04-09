jQuery(document).ready(function(){    
    drawVotesGraph();
    
    if (typeof petitionSigningBeforeTime !== 'undefined'){
        initializeClock('petition-days-left-clock', new Date(Date.parse(petitionSigningBeforeTime)));
    }
    
	jQuery('#put-petition').click(function(){
		var data = {};
		data.id = jQuery(this).attr('data-id');
		
		jQuery.ajax({
			url: "index.php?option=com_jpetition&view=petition&layout=vote&xhr=1",
			cache: false,
			data: data,
			dataType: "json",
			method: "POST",
			beforeSend: function(xhr) {
				
			}
		}).done(function(data) {
            if (data.response){
                var countVotesSigns = (jQuery('#petition-votes-graph').attr('data-signs') * 1) + 1;                
                jQuery('#petition-votes-graph').attr({'data-signs': countVotesSigns});
                jQuery('#petition-count-votes').text(countVotesSigns);
                jQuery('#petition-count-votes-graph').text(countVotesSigns);
                jQuery('#put-petition').remove();
                jQuery('#petition-sign').append('<div class="petition-was-signed">' + data.message + '</div>');
                drawVotesGraph();
            } else {
                if (typeof data.message !== 'undefined'){
                    alert(data.message);
                } else {
                    alert('Undefined Error');
                }
            }
		});
	});
});

function drawVotesGraph() {
	var canvas = jQuery('#petition-votes-graph');
	if (canvas.length > 0) {
		var x = 125,
			y = 125,
			radius = 104,
			initialArcPosition = 1.5,
			finishArcPosition = 1.4999999,
			signs = canvas.attr('data-signs') * 1,
			neededSigns = canvas.attr('data-needed-signs') * 1,
			context = canvas[0].getContext('2d'),
			signsCoef = signs / neededSigns,
			endPosition = initialArcPosition + signsCoef * 2,
			startAngle = initialArcPosition * Math.PI;

		if (signsCoef > 1) {
			endPosition = finishArcPosition;
		}		
		
		var endAngle = endPosition * Math.PI;

		// draw full arc
		context.beginPath();
		context.arc(x, y, radius, startAngle, finishArcPosition * Math.PI, false);
		context.lineWidth = 40;
		context.strokeStyle = '#dddddd';
		context.stroke();

		// draw part of arc
		context.beginPath();
		context.arc(x, y, radius, startAngle, endAngle, false);
		context.lineWidth = 40;
		context.strokeStyle = "#2424AA";
		context.stroke();
	}
}

function getTimeRemaining(endtime) {
	var t = Date.parse(endtime) - Date.parse(new Date());
	var seconds = Math.floor((t / 1000) % 60);
	var minutes = Math.floor((t / 1000 / 60) % 60);
	var hours = Math.floor((t / (1000 * 60 * 60)) % 24);

	return {
		'total': t,
		'hours': hours,
		'minutes': minutes,
		'seconds': seconds
	};
}

function initializeClock(id, endtime) {
	var clock = document.getElementById(id);
	if (clock !== null){
		var hoursSpan = clock.querySelector('.hours');
		var minutesSpan = clock.querySelector('.minutes');
		var secondsSpan = clock.querySelector('.seconds');

		function updateClock() {
			var t = getTimeRemaining(endtime);

			hoursSpan.innerHTML = ('0' + t.hours).slice(-2);
			minutesSpan.innerHTML = ('0' + t.minutes).slice(-2);
			secondsSpan.innerHTML = ('0' + t.seconds).slice(-2);

			if (t.total <= 0) {
				jQuery('#petition-days-left').text(petitionSigningFinishText);
				jQuery('#petition-sign').remove();
				clearInterval(timeinterval);
			}
		}

		updateClock();
		var timeinterval = setInterval(updateClock, 1000);
	}
}