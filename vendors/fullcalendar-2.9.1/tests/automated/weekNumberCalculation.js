
describe('weekNumberCalculation', function() {

	var options;

	beforeEach(function() {
		options = {
			weekNumbers: true
		};
	});

	function getRenderedWeekText() {
		// works for both kinds of views
		return $('.fc-agenda-view .fc-week-number, .fc-week:first .fc-content-skeleton .fc-week-number').text();
	}

	function getRenderedWeekNumber() {
		var text = getRenderedWeekText() || '';
		return parseInt(text.replace(/\D/g, ''), 10);
	}

	beforeEach(function() {
		affix('#cal');
	});

	[ 'basicDay', 'agendaDay' ].forEach(function(viewType) {
		describe('when in ' + viewType + ' view', function() {

			beforeEach(function() {
				options.defaultView = viewType;
			});

			it('should display the American standard when using \'local\'', function() {
				options.defaultDate = '2013-11-23'; // a Saturday
				options.weekNumberCalculation = 'local';
				$('#cal').fullCalendar(options);
				expect(getRenderedWeekNumber()).toBe(47);
			});

			it('should display a language-specific local week number', function() {
				options.defaultDate = '2013-11-23'; // a Saturday
				options.lang = 'ar';
				options.weekNumberCalculation = 'local';
				$('#cal').fullCalendar(options);
				expect(getRenderedWeekText()).toMatch(/٤٨|48/);
			});

			// another local test, but to make sure it is different from ISO
			it('should display the American standard when using \'local\'', function() {
				options.defaultDate = '2013-11-17'; // a Sunday
				options.weekNumberCalculation = 'local';
				$('#cal').fullCalendar(options);
				expect(getRenderedWeekNumber()).toBe(47);
			});

			it('should display ISO standard when using \'ISO\'', function() {
				options.defaultDate = '2013-11-17'; // a Sunday
				options.weekNumberCalculation = 'ISO';
				$('#cal').fullCalendar(options);
				expect(getRenderedWeekNumber()).toBe(46);
			});

			it('should display the calculated number when a custom function', function() {
				options.weekNumberCalculation = function() {
					return 4;
				};
				$('#cal').fullCalendar(options);
				expect(getRenderedWeekNumber()).toBe(4);
			});
		});
	});
});