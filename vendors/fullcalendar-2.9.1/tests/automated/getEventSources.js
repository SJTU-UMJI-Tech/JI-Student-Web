describe('getEventSources', function() {
	var options;
	var calendarEl;

	beforeEach(function() {
		affix('#cal');
		calendarEl = $('#cal');
		options = {
			now: '2015-08-07',
			defaultView: 'agendaWeek',
			eventSources: [
				{
					events: [
						{ id: 1, start: '2015-08-07T02:00:00', end: '2015-08-07T03:00:00', title: 'event A' }
					]
				},
				{
					events: [
						{ id: 2, start: '2015-08-07T03:00:00', end: '2015-08-07T04:00:00', title: 'event B' }
					]
				},
				{
					events: [
						{ id: 3, start: '2015-08-07T04:00:00', end: '2015-08-07T05:00:00', title: 'event C' }
					]
				}
			]
		};
	});

	it('does not mutate when removeEventSource is called', function(done) {
		var eventSources;

		calendarEl.fullCalendar(options);

		eventSources = calendarEl.fullCalendar('getEventSources');
		expect(eventSources.length).toBe(3);

		// prove that eventSources is a copy, and wasn't mutated
		calendarEl.fullCalendar('removeEventSource', eventSources[0]);
		expect(eventSources.length).toBe(3);

		done();
	});
});