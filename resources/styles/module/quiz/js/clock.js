$(document).ready(function () {
	// Create two variable with the names of the months and days in an array
	var monthNames = ["Tháng 1", "Tháng 2", "Tháng 3", "Tháng 4", "Tháng 5", "Tháng 6", "Tháng 7", "Tháng 8", "Tháng 9", "Tháng 10", "Tháng 11", "Tháng 12"];
	var dayNames = ["Chủ nhật", "Thứ hai", "Thứ ba", "Thứ tư", "Thứ năm", "Thứ sáu", "Thứ bảy"];

	var monthNames_EN = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
	var dayNames_EN = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];

	var lang = $('#lang').text();

	// Create a newDate() object
	var newDate = new Date();
	// Extract the current date from Date object
	//newDate.setDate(newDate.getDate());
	// Output the day, date, month and year
	if (lang == 'vi') {
		$('#Date').html(dayNames[newDate.getDay()] + ", " + newDate.getDate() + ' ' + monthNames[newDate.getMonth()] + ', ' + newDate.getFullYear());
	} else {
		$('#Date').html(dayNames_EN[newDate.getDay()] + ", " + monthNames_EN[newDate.getMonth()] + ' ' + newDate.getDate() + ', ' + newDate.getFullYear());
	}

	/*setInterval( function() {
		// Create a newDate() object and extract the seconds of the current time on the visitor's
		var seconds = newDate.getSeconds();
		// Add a leading zero to seconds value
		$("#sec").html(( seconds < 10 ? "0" : "" ) + seconds);
		},1000);
	
	setInterval( function() {
		// Create a newDate() object and extract the minutes of the current time on the visitor's
		var minutes = newDate.getMinutes();
		// Add a leading zero to the minutes value
		$("#min").html(( minutes < 10 ? "0" : "" ) + minutes);
		},1000);
	
	setInterval( function() {
		// Create a newDate() object and extract the hours of the current time on the visitor's
		var hours = newDate.getHours();
		// Add a leading zero to the hours value
		$("#hours").html(( hours < 10 ? "0" : "" ) + hours);
		}, 1000);	*/
});
