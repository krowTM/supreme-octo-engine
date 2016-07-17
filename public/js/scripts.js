var App = {
	import: function(e) {
		e.preventDefault();
		var request = $.ajax({
			method: "POST",
			url: "import",
			headers: {
				"X-CSRF-TOKEN": $("meta[name=\"csrf-token\"]").attr("content")
		    }
		});
		this.checkImportProgress();
	},
	checkImportProgress: function() {
		var runInterval = setInterval(function() {
			$.getJSON("progress/progress.json?" + (Math.random(10000000) * 10000000 + 1), function(json) {
				console.log(json);
				$(".setup-button").html(json);
				if (json == "Done.") {
					clearInterval(runInterval);
				}
			})
		}, 500);
	},
	charts: function(e) {
		e.preventDefault();
		var request = $.ajax({
			method: "POST",
			url: "charts",
			headers: {
				"X-CSRF-TOKEN": $("meta[name=\"csrf-token\"]").attr("content")
		    }
		});
		request.done(function(data) {
			$(".result").html(data);
		});
	},
	generateChart: function(e, type) {
		e.preventDefault();
		var request = $.ajax({
			method: "POST",
			url: "generate-chart",
			data: {
				type: type
			},
			headers: {
				"X-CSRF-TOKEN": $("meta[name=\"csrf-token\"]").attr("content")
		    }
		});
		request.done(function(data) {
			$("#chart").html(data);
		});
	}
};