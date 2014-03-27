
define(['jquery', 'localization', 'notifications', 'spinner', 'text!templates/statistic.html', 'dateRangePicker'],
	function($, localization, notifications, spinner, StatisticTemplate) {

		return  Backbone.View.extend({
			template: _.template(StatisticTemplate),
			className: 'dashboard',
			//countries:null,
			startDate: null,
			endDate: null,
			initialize: function(options) {
				_.bindAll(this);

				this.startDate = options.fromDate;
				this.endDate = options.toDate;

				if (!this.model.id) {
					spinner.stop();
				}

				//Bind rangepicker change event
				this.on('change:range', this.rangeChanged);

				this.render();
			},
			events: {
			},
			bindings: {
				'#sent': 'sent',
				'#recovered': 'recovered',
				'#recoveredPercent': {
					observe: 'recoveredPercent',
					onGet: function(value) {
						return Math.round(value) + '%';
					}
				},
				'#recoveredPercentBar': {
					attributes: [{
							name: 'style',
							observe: 'recoveredPercent',
							onGet: function(value) {
								return 'width: ' + Math.round(value) + '%;';
							}
						}]
				},
				'#completed': 'completed',
				'#completedPercent': {
					observe: "completedPercent",
					onGet: function(value) {
						return Math.round(value) + '%';
					}
				},
				'#completedPercentBar': {
					attributes: [{
							name: 'style',
							observe: 'completedPercent',
							onGet: function(value) {
								return 'width: ' + Math.round(value) + '%;';
							}
						}]
				}
			},
			rangeChanged: function(range) {
				if (range.start === null && range.end === null) {
					Backbone.history.navigate('', {
						trigger: true
					});
				} else {
					Backbone.history.navigate('statistics/' + range.start + '/' + range.end, {
						trigger: true
					});
				}

			},
			render: function() {
				var self = this;
				$(this.el).html(this.template(localization.toJSON()));

				this.$('#nav a').click(function(e) {
					e.preventDefault();
					$(this).tab('show');
				});

				/*Bind model to view*/
				this.stickit();
				/*Bind Validation*/
				Backbone.Validation.bind(this, {
					//Important! this allow models to be updated with invalid values.
					//This way the validation behave correctly when the form fields 
					//are invalid
					forceUpdate: true
				});


				var self = this;
				//prepare datepicker
				var myRanges = {};
				//myRanges[localization.get('lastDonations')]=[null,null];
				myRanges[localization.get('today')] = ['today', 'today'];
				myRanges[localization.get('yesterday')] = ['yesterday', 'yesterday'];
				myRanges[localization.get('lastSevenDays')] = [Date.today().add({
						days: -6
					}), 'today'];
				myRanges[localization.get('lastThirtyDays')] = [Date.today().add({
						days: -29
					}), 'today'];
				myRanges[localization.get('thisMonth')] = [Date.today().moveToFirstDayOfMonth(), Date.today().moveToLastDayOfMonth()];
				myRanges[localization.get('lastMonth')] = [Date.today().moveToFirstDayOfMonth().add({
						months: -1
					}), Date.today().moveToFirstDayOfMonth().add({
						days: -1
					})];

				this.$('#form-date-range').daterangepicker({
					ranges: myRanges,
					opens: 'left',
					format: Date.CultureInfo.formatPatterns.shortDate, // 'MM/dd/yyyy',
					separator: ' to ',
					startDate: Date.today().add({
						days: -29
					}),
					endDate: Date.today(),
					maxDate: Date.today(),
					locale: {
						applyLabel: localization.get('rangeApplyLabel'),
						fromLabel: localization.get('rangeFromLabel'),
						toLabel: localization.get('rangeToLabel'),
						customRangeLabel: localization.get('rangeLabel'),
						//daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
						//monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
						firstDay: 1
					},
					showWeekNumbers: true,
					buttonClasses: ['btn-danger']
				}, function(start, end) {
					self.startDate = (start == null ? null : start.toString('yyyy-MM-dd'));
					self.endDate = (end == null ? end : end.toString('yyyy-MM-dd'));

					self.trigger('change:range', {
						start: self.startDate,
						end: self.endDate
					});
				});

				//Set initial range
				if (this.startDate == null || this.endDate == null) {
					this.$('#form-date-range span').html(localization.get('noRange'));
				} else {
					this.$('#form-date-range span').html(this.startDate.toString(Date.CultureInfo.formatPatterns.shortDate) + ' - ' + this.endDate.toString(Date.CultureInfo.formatPatterns.shortDate));
				}

				return this;
			}
		});

	});










