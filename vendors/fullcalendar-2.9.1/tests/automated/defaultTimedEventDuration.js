describe('defaultTimedEventDuration', function() {

	var options;

	beforeEach(function() {
		affix('#cal');

		options = {
			defaultDate: '2014-05-01',
			defaultView: 'month'
		};
	});

	describe('when forceEventDuration is on', function() {

		beforeEach(function() {
			options.forceEventDuration = true;
		});

		it('correctly calculates an unspecified end when using a Duration object input', function() {
			options.defaultTimedEventDuration = { hours: 2, minutes: 30 };
			options.events = [
				{
					allDay: false,
					start: '2014-05-05T04:00:00'
				}
			];
			$('#cal').fullCalendar(options);
			var event = $('#cal').fullCalendar('clientEvents')[0];
			expect(event.end).toEqualMoment('2014-05-05T06:30:00');
		});

		it('correctly calculates an unspecified end when using a string Duration input', function() {
			options.defaultTimedEventDuration = '03:15:00';
			options.events = [
				{
					allDay: false,
					start: '2014-05-05T04:00:00'
				}
			];
			$('#cal').fullCalendar(options);
			var event = $('#cal').fullCalendar('clientEvents')[0];
			expect(event.end).toEqualMoment('2014-05-05T07:15:00');
		});
	});

	describe('when forceEventDuration is off', function() {

		beforeEach(function() {
			options.forceEventDuration = false;
		});

		describe('with agendaWeek view', function() {
			beforeEach(function() {
				options.defaultView = 'agendaWeek';
			});
			it('renders a timed event with no `end` to appear to have the default duration', function(done) {
				options.defaultTimedEventDuration = '01:15:00';
				options.events = [
					{
						// a control. so we know how tall it should be
						title: 'control event',
						allDay: false,
						start: '2014-05-01T04:00:00',
						end: '2014-05-01T05:15:00'
					},
					{
						// one day after the control. no specified end
						title: 'test event',
						allDay: false,
						start: '2014-05-02T04:00:00'
					}
				];
				options.eventAfterAllRender = function() {
					var eventElms = $('#cal .fc-event');
					var height0 = eventElms.eq(0).outerHeight();
					var height1 = eventElms.eq(1).outerHeight();
					expect(height0).toEqual(height1);
					done();
				};
				$('#cal').fullCalendar(options);
			});
		});

		describe('with basicWeek view', function() {
			beforeEach(function() {
				options.defaultView = 'basicWeek';
			});
			it('renders a timed event with no `end` to appear to have the default duration', function(done) {
				options.defaultTimedEventDuration = { days: 2 };
				options.events = [
					{
						// a control. so we know how wide it should be
						title: 'control event',
						allDay: false,
						start: '2014-04-28T04:00:00',
						end: '2014-04-30T00:00:00'
					},
					{
						// one day after the control. no specified end
						title: 'test event',
						allDay: false,
						start: '2014-04-28T04:00:00'
					}
				];
				options.eventAfterAllRender = function() {
					var eventElms = $('#cal .fc-event');
					var width0 = eventElms.eq(0).outerWidth();
					var width1 = eventElms.eq(1).outerWidth();
					expect(width0).toEqual(width1);
					done();
				};
				$('#cal').fullCalendar(options);
			});
		});
	});
});