describe('getEventSource', function() {
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
					],
					id: 'source1'
				},
				{
					events: [
						{ id: 2, start: '2015-08-07T03:00:00', end: '2015-08-07T04:00:00', title: 'event B' }
					],
					id: 'source2'
				},
				{
					events: [
						{ id: 3, start: '2015-08-07T04:00:00', end: '2015-08-07T05:00:00', title: 'event C' }
					],
					id: 'source3'
				}
			]
		};
	});

	it('retreives the queried event source', function(done) {
		var eventSource1;
		var eventSource2;

		calendarEl.fullCalendar(options);

		eventSource1 = calendarEl.fullCalendar('getEventSourceById', 'source1');
		eventSource2 = calendarEl.fullCalendar('getEventSourceById', 'source2');

		expect(eventSource1.id).toBe('source1');
		expect(eventSource2.id).toBe('source2');

		done();
	});
});