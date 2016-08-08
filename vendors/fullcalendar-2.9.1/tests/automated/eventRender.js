describe('eventRender', function() {
	var options;

	beforeEach(function() {
		options = {
			defaultDate: '2014-11-12',
			scrollTime: '00:00:00'
		};
		affix('#cal');
	});

	$.each({
		month: '.fc-day-grid',
		agendaWeek: '.fc-time-grid'
	}, function(viewName, gridSelector) {
		describe('when in ' + viewName + ' view', function() {
			beforeEach(function() {
				options.defaultView = viewName;
			});
			describe('with foreground event', function() {
				it('receives correct args AND can modify the element', function(done) {
					options.events = [ {
						title: 'my event',
						start: '2014-11-12T09:00:00'
					} ];
					options.eventRender = function(event, element, view) {
						expect(typeof event).toBe('object');
						expect(event.rendering).toBeUndefined();
						expect(event.start).toBeDefined();
						expect(typeof element).toBe('object');
						expect(element.length).toBe(1);
						expect(typeof view).toBe('object');
						element.css('font-size', '20px');
					};
					options.eventAfterAllRender = function() {
						expect($(gridSelector).find('.fc-event').css('font-size')).toBe('20px');
						expect(options.eventRender).toHaveBeenCalled();
						done();
					};
					spyOn(options, 'eventRender').and.callThrough();
					$('#cal').fullCalendar(options);
				});
			});
		});
	});

	describe('when in month view', function() {
		beforeEach(function() {
			options.defaultView = 'month';
		});

		describe('with a foreground event', function() {
			beforeEach(function() {
				options.events = [ {
					title: 'my event',
					start: '2014-11-12'
				} ];
			});
			it('can return a new element', function(done) {
				options.eventRender = function(event, element, view) {
					return $('<div class="fc-event sup" style="background-color:green">sup g</div>');
				};
				options.eventAfterAllRender = function() {
					expect($('.fc-day-grid .sup').length).toBe(1);
					expect(options.eventRender).toHaveBeenCalled();
					done();
				};
				spyOn(options, 'eventRender').and.callThrough();
				$('#cal').fullCalendar(options);
			});
			it('can return false and cancel rendering', function(done) {
				options.eventRender = function(event, element, view) {
					return false;
				};
				options.eventAfterAllRender = function() {
					expect($('.fc-day-grid .fc-event').length).toBe(0);
					expect(options.eventRender).toHaveBeenCalled();
					done();
				};
				spyOn(options, 'eventRender').and.callThrough();
				$('#cal').fullCalendar(options);
			});
		});

		describe('with an all-day background event', function() {
			beforeEach(function() {
				options.events = [ {
					title: 'my event',
					start: '2014-11-12',
					rendering: 'background'
				} ];
			});
			it('receives correct args AND can modify the element', function(done) {
				options.eventRender = function(event, element, view) {
					expect(typeof event).toBe('object');
					expect(event.rendering).toBe('background');
					expect(event.start).toBeDefined();
					expect(typeof element).toBe('object');
					expect(element.length).toBe(1);
					expect(typeof view).toBe('object');
					element.css('font-size', '20px');
				};
				options.eventAfterAllRender = function() {
					expect($('.fc-day-grid .fc-bgevent').css('font-size')).toBe('20px');
					expect(options.eventRender).toHaveBeenCalled();
					done();
				};
				spyOn(options, 'eventRender').and.callThrough();
				$('#cal').fullCalendar(options);
			});
			it('can return a new element', function(done) {
				options.eventRender = function(event, element, view) {
					return $('<td class="sup" style="background-color:green">sup g</td>');
				};
				options.eventAfterAllRender = function() {
					expect($('.fc-day-grid .sup').length).toBe(1);
					expect(options.eventRender).toHaveBeenCalled();
					done();
				};
				spyOn(options, 'eventRender').and.callThrough();
				$('#cal').fullCalendar(options);
			});
			it('won\'t rendering when returning a new element of the wrong type', function(done) {
				options.eventRender = function(event, element, view) {
					return $('<div class="sup" style="background-color:green">sup g</div>');
				};
				options.eventAfterAllRender = function() {
					expect($('.fc-day-grid .sup').length).toBe(0);
					expect(options.eventRender).toHaveBeenCalled();
					done();
				};
				spyOn(options, 'eventRender').and.callThrough();
				$('#cal').fullCalendar(options);
			});
			it('can return false and cancel rendering', function(done) {
				options.eventRender = function(event, element, view) {
					return false;
				};
				options.eventAfterAllRender = function() {
					expect($('.fc-day-grid .fc-bgevent').length).toBe(0);
					expect(options.eventRender).toHaveBeenCalled();
					done();
				};
				spyOn(options, 'eventRender').and.callThrough();
				$('#cal').fullCalendar(options);
			});
		});

		describe('with a timed background event', function() { // not exactly related to eventRender!
			beforeEach(function() {
				options.events = [ {
					title: 'my event',
					start: '2014-11-12T01:00:00',
					rendering: 'background'
				} ];
			});
			it('won\'t render or call eventRender', function(done) {
				options.eventRender = function(event, element, view) { };
				options.eventAfterAllRender = function() {
					expect($('.fc-day-grid .fc-bgevent').length).toBe(0);
					expect(options.eventRender).not.toHaveBeenCalled();
					done();
				};
				spyOn(options, 'eventRender').and.callThrough();
				$('#cal').fullCalendar(options);
			});
		});
	});

	describe('when in agendaWeek view', function() {
		beforeEach(function() {
			options.defaultView = 'agendaWeek';
		});

		describe('with a foreground event', function() {
			beforeEach(function() {
				options.events = [ {
					title: 'my event',
					start: '2014-11-12T01:00:00'
				} ];
			});
			it('can return a new element', function(done) {
				options.eventRender = function(event, element, view) {
					return $('<div class="fc-event sup" style="background-color:green">sup g</div>');
				};
				options.eventAfterAllRender = function() {
					expect($('.fc-time-grid .sup').length).toBe(1);
					expect(options.eventRender).toHaveBeenCalled();
					done();
				};
				spyOn(options, 'eventRender').and.callThrough();
				$('#cal').fullCalendar(options);
			});
			it('can return false and cancel rendering', function(done) {
				options.eventRender = function(event, element, view) {
					return false;
				};
				options.eventAfterAllRender = function() {
					expect($('.fc-time-grid .fc-event').length).toBe(0);
					expect(options.eventRender).toHaveBeenCalled();
					done();
				};
				spyOn(options, 'eventRender').and.callThrough();
				$('#cal').fullCalendar(options);
			});
		});

		describe('with a timed background event', function() {
			beforeEach(function() {
				options.events = [ {
					title: 'my event',
					start: '2014-11-12T01:00:00',
					rendering: 'background'
				} ];
			});
			it('receives correct args AND can modify the element', function(done) {
				options.eventRender = function(event, element, view) {
					expect(typeof event).toBe('object');
					expect(event.rendering).toBe('background');
					expect(event.start).toBeDefined();
					expect(typeof element).toBe('object');
					expect(element.length).toBe(1);
					expect(typeof view).toBe('object');
					element.css('font-size', '20px');
				};
				options.eventAfterAllRender = function() {
					expect($('.fc-time-grid .fc-bgevent').css('font-size')).toBe('20px');
					expect(options.eventRender).toHaveBeenCalled();
					done();
				};
				spyOn(options, 'eventRender').and.callThrough();
				$('#cal').fullCalendar(options);
			});
			it('can return a new element', function(done) {
				options.eventRender = function(event, element, view) {
					return $('<div class="fc-bgevent sup" style="background-color:green">sup g</div>');
				};
				options.eventAfterAllRender = function() {
					expect($('.fc-time-grid .sup').length).toBe(1);
					expect(options.eventRender).toHaveBeenCalled();
					done();
				};
				spyOn(options, 'eventRender').and.callThrough();
				$('#cal').fullCalendar(options);
			});
			it('won\'t rendering when returning a new element of the wrong type', function(done) {
				options.eventRender = function(event, element, view) {
					return $('<p class="fc-bgevent sup" style="background-color:green">sup g</p>');
				};
				options.eventAfterAllRender = function() {
					expect($('.fc-time-grid .sup').length).toBe(0);
					expect(options.eventRender).toHaveBeenCalled();
					done();
				};
				spyOn(options, 'eventRender').and.callThrough();
				$('#cal').fullCalendar(options);
			});
			it('can return false and cancel rendering', function(done) {
				options.eventRender = function(event, element, view) {
					return false;
				};
				options.eventAfterAllRender = function() {
					expect($('.fc-time-grid .fc-bgevent').length).toBe(0);
					expect(options.eventRender).toHaveBeenCalled();
					done();
				};
				spyOn(options, 'eventRender').and.callThrough();
				$('#cal').fullCalendar(options);
			});
		});

		describe('with an all-day background event', function() { // not exactly related to eventRender!
			beforeEach(function() {
				options.events = [ {
					title: 'my event',
					start: '2014-11-12',
					rendering: 'background'
				} ];
			});
			it('will render in the all-day slot', function(done) {
				options.eventRender = function(event, element, view) { };
				options.eventAfterAllRender = function() {
					expect($('.fc-day-grid .fc-bgevent').length).toBe(1);
					expect($('.fc-time-grid .fc-bgevent').length).toBe(0);
					expect(options.eventRender).toHaveBeenCalled();
					done();
				};
				spyOn(options, 'eventRender').and.callThrough();
				$('#cal').fullCalendar(options);
			});
		});
	});
});