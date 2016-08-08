describe('refetchEvents', function() {
	describe('when agenda events are rerendered', function() {
		beforeEach(function() {
			affix('#cal');
		});

		it('keeps scroll after refetchEvents', function(done) {
			var renderCalls = 0;

			$('#cal').fullCalendar({
				now: '2015-08-07',
				scrollTime: '00:00',
				height: 400, // makes this test more consistent across viewports
				defaultView: 'agendaDay',
				events: function(start, end, timezone, callback) {
					setTimeout(function() {
						callback([
							{ id: '1', resourceId: 'b', start: '2015-08-07T02:00:00', end: '2015-08-07T07:00:00', title: 'event 1' },
							{ id: '2', resourceId: 'c', start: '2015-08-07T05:00:00', end: '2015-08-07T22:00:00', title: 'event 2' },
							{ id: '3', resourceId: 'd', start: '2015-08-06', end: '2015-08-08', title: 'event 3' },
							{ id: '4', resourceId: 'e', start: '2015-08-07T03:00:00', end: '2015-08-07T08:00:00', title: 'event 4' },
							{ id: '5', resourceId: 'f', start: '2015-08-07T00:30:00', end: '2015-08-07T02:30:00', title: 'event 5' }
						]);
					}, 100);
				},
				eventAfterAllRender: function() {
					var scrollEl = $('.fc-time-grid-container.fc-scroller');
					renderCalls++;
					if (renderCalls == 1) {
						setTimeout(function() {
							scrollEl.scrollTop(100);
							setTimeout(function() {
								$('#cal').fullCalendar('refetchEvents');
							}, 100);
						}, 100);
					}
					else if (renderCalls == 2) {
						expect(scrollEl.scrollTop()).toBe(100);
						done();
					}
				}
			});
		});
	});

	describe('when there are multiple event sources', function() {
		var options;
		var fetchCount; // affects events created in createEventGenerator
		var calendarEl;

		beforeEach(function() {
			affix('#cal');
			fetchCount = 0;
			calendarEl = $('#cal');
			options = {
				now: '2015-08-07',
				defaultView: 'agendaWeek',
				eventSources: [
					{
						events: createEventGenerator(),
						color: 'green',
						id: 'source1'
					},
					{
						events: createEventGenerator(),
						color: 'blue',
						id: 'source2'
					},
					{
						events: createEventGenerator(),
						color: 'red',
						id: 'source3'
					}
				]
			};
		});

		describe('and all events are fetched synchronously', function() {
			it('all events are immediately updated', function(done) {
				calendarEl.fullCalendar(options);
				fetchCount++;
				calendarEl.fullCalendar('refetchEvents');
				expect($('.fetch0').length).toEqual(0);
				expect($('.fetch1').length).toEqual(3);
				done();
			});
		});

		describe('and one event source is asynchronous', function() {
			it('original events remain on the calendar until all events have been refetched', function(done) {
				// set a 100ms timeout on this event source
				options.eventSources[0].events = function(start, end, timezone, callback) {
					var events = [
						{ id: '1',
						  start: '2015-08-07T02:00:00',
						  end: '2015-08-07T03:00:00',
						  title: 'event A', className: 'fetch' + fetchCount
						}
					];

					setTimeout(function() {
						callback(events);
					}, 100);
				};
				options.eventAfterAllRender = function() {
					fetchCount++;
					if (fetchCount === 1) {
						// after the initial rendering of events, call refetchEvents
						calendarEl.fullCalendar('refetchEvents');

						expect($('.fetch0').length).toEqual(3); // original events still on the calendar
						expect($('.fetch1').length).toEqual(0); // new events not yet refetched

						setTimeout(function() { // wait until refetch+rerender is over
							expect($('.fetch0').length).toEqual(0);
							expect($('.fetch1').length).toEqual(3);
							done();
						}, 101);
					}
				};

				calendarEl.fullCalendar(options);
			});
		});

		// relies on fetchCount
		function createEventGenerator() {
			return function(start, end, timezone, callback) {
				var events = [
					{
					  id: 1,
					  start: '2015-08-07T02:00:00',
					  end: '2015-08-07T03:00:00',
					  title: 'event A',
					  className: 'fetch' + fetchCount
					}
				];

				callback(events);
			};
		}
	});
});
