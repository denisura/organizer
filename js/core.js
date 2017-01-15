$(document).ready(function() {
	if ($('span.tag').size() > 0) {
		var tag = $('span.tag').get().sort(function() {
			return Math.round(Math.random()) - 0.5
		}).slice(0,1);
		$("#photos").load("index.php?controller=contact&action=interest&tag="+$(tag).text());
	}else if($('#photos').size() > 0){
		$('#photos').empty();
	}
});