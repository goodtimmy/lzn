@extends("layouts.app")
@section("content")
@section('head_title', __('For groups | Beer baths in Prague') )

@include("_particles.vuejs")

<header id="home" class="jumbotron bg-inverse text-center center-vertically contacts_page">
<div class="container">
	<h1 class="display-3 m-b-lg animated fadeInDown">{{__('For groups')}}</h1>
	<a class="btn btn-secondary-outline m-b-md animated fadeInUp slideble" href="#reservation" role="button">{{__('Reservation')}}<br><span>{{__('for groups and companies')}}</span></a>
	<div class="list-inline social-share animated fadeInUp">
		<a title="{{__('Beer baths in Prague on Tripadvisor')}}" href="https://www.tripadvisor.com/Attraction_Review-g274707-d7377900-Reviews-Lazne_Pramen_Beer_and_Wine_spa-Prague_Bohemia.html" target="_blank"><img src="{{ URL::asset('assets/img/tripadvisor_white.svg') }}" alt="{{__('Beer baths in Prague')}}" /><br>
		<span>{{__('Tripadvisor header')}}</p></span></a>
	</div>
</div>
</header>

<section id="reservation" class="section_reservation">
	<div id="loader-layout" v-if="loader">
		<img src="{{ URL::to('assets/img/loader.svg') }}">
	</div>
	<div class="container animated fadeInUp">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="section_reservation_inside" v-if="!groupRequestSuccess">
					<h2 class="text-center">{{__('Reservation beer baths in Prague for groups')}}</h2>
					<p class="text-center">{{__('Choose a date and time of visit, enter a number of people and your contact details')}}.</p>
					<form class="beer_baths_reservation_form beer_baths_reservation_form_groups">
						<div class="section_reservation_inside_block personal_data_block">
							<div class="row">
								<div class="col-md-4 col-sm-12 col-xs-12">
									<div class="form-group">
										<label for="date" class="form-label">
											<i class="fas fa-calendar-alt"></i>
											{{__('Date')}}</label>
										<vuejs-datepicker
												v-model="selectedDate"
												:format="customFormatted"
												:monday-first="true"
												class="form-control"
												id="date"
												:disabled-dates="disabledDates"
												:language="{{Lang::locale()}}"
										></vuejs-datepicker>
									</div>
								</div>
								<div class="col-md-4 col-sm-12 col-xs-12">
									<div class="form-group">
										<label for="time" class="form-label">
											<i class="fas fa-clock"></i>
											{{__('Time')}}</label>
										<select v-model="selectedHour" class="form-control" id="time" required="" data-validation-required-message="Choose time" aria-invalid="false">
											<option v-for="option in hours" :disabled="!option.enabled">
												@{{ option.value }}
											</option>
										</select>
									</div>
								</div>
								<div class="col-md-4 col-sm-12 col-xs-12">
									<div class="form-group">
										<label for="persons" class="form-label">
											<i class="fas fa-user-friends"></i>
											{{__('Number of persons')}}</label>
										<input v-model="selectedPersons" class="form-control" value="1" id="persons" type="number" required="" data-validation-required-message="Enter number of persons" aria-invalid="false" />
									</div>
								</div>
								<div class="col-md-4 col-sm-12 col-xs-12">
									<div class="form-group">
										<label for="name" class="form-label">
											<i class="fas fa-user"></i>
											{{__('Full name or company name')}}</label>
										<input v-model="userInfo['name']" class="form-control" id="name" type="text" required="" data-validation-required-message="Enter your full name or company name" aria-invalid="false" />
									</div>
								</div>
								<div class="col-md-4 col-sm-12 col-xs-12">
									<div class="form-group">
										<label for="email" class="form-label">
											<i class="fas fa-envelope"></i>
											{{__('Email')}}</label>
										<input v-model="userInfo['email']" class="form-control" id="email" type="email" required="" data-validation-required-message="Enter your email" aria-invalid="false" />
									</div>
								</div>
								<div class="col-md-4 col-sm-12 col-xs-12">
									<div class="form-group">
										<label for="phone" class="form-label">
											<i class="fas fa-phone"></i>
											{{__('Phone')}}</label>
										<input v-model="userInfo['phone']" class="form-control" id="phone" type="tel" required="" data-validation-required-message="Enter your phone number" aria-invalid="false" />
									</div>
								</div>
								<div class="col-md-8 col-sm-12 col-xs-12">
									<div class="form-group">
										<label for="message" class="form-label">
											<i class="fas fa-comment"></i>
											{{__('Message')}} <span>({{__('specify your wishes')}})</span></label>
										<textarea v-model="userInfo['message']" class="form-control" id="message" rows="5" placeholder="" required="" data-validation-required-message="Enter your message" aria-invalid="false"></textarea>
									</div>
									{{--
									<div class="form-check">
										<input v-model="userInfo['terms']" type="checkbox" class="form-check-input" id="beer_baths_conditions">
										<label class="form-check-label" for="beer_baths_conditions">{{__('I accept terms and conditions')}}.<!-- <a href="{{ route('terms-and-conditions') }}" target="_blank">{{__('the terms and conditions Beer-Baths.com')}}</a>.--></label>
									</div>
									--}}
								</div>
								<div class="col-md-12 col-sm-12 col-xs-12">
									<div class="form-group">
										<div v-on:click="groupRequest" v-bind:class="{ disabled: OrderDisabled() }" class="summary_block order">
											<a class="order_button group_order_button">
												{{__('Send request')}}
											</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
				<div v-if="groupRequestSuccess" class="group_reservation_form_submit_result">
					<h1>Success :-)</h1>
					<h2>{{__('Your order has been placed, we will connect with you shortly')}}</h2>
					<h2>{{__('Meanwhile, you can')}} <a href="{{ route('photo-video') }}">{{__('take a look at the photos and videos of our salon')}}</a>.</h2>
				</div>
			</div>
		</div>
	</div>
</section>


<script>
	var app = new Vue({
		el: '#reservation',
		data: {
			loader: false,
			message: null,
			hasError: false,
			today: new Date,
			selectedDate: '',
			selectedHour: null,
			selectedPersons: null,
			userInfo: [],
			hours: [],
			maxB1: {{ $maxB1 }},
			maxB2: {{ $maxB2 }},
			disabledDates: {
				to: new Date('{{now()}}'), // Disable all dates up to specific date (till today)
				from: new Date('{{ date('Y-m-d', strtotime('+1 year')) }}'), // Disable all dates after specific date (from next year)
				dates: [ // Disable an array of dates
					new Date(2016, 9, 16),
					new Date(2016, 9, 17),
					new Date(2016, 9, 18)
				]
			},
			disabledHours: {},
			openHours: {{ abs( env('open_period_from', 11) - env('open_period_till', 21) )+1 }},
			openFrom: {{ env('open_period_from', 11) }},
			pluralData: {
				single_baths: [
					"{{ trans_choice('n single baths', 1, ['count' => 1]) }}",
					"{{ trans_choice('n single baths', 2, ['count' => 2]) }}",
					"{{ trans_choice('n single baths', 5, ['count' => 5]) }}"
				],
				double_baths: [
					"{{ trans_choice('n double baths', 1, ['count' => 1]) }}",
					"{{ trans_choice('n double baths', 2, ['count' => 2]) }}",
					"{{ trans_choice('n double baths', 5, ['count' => 5]) }}"
				],
			},
			groupRequestSuccess: null,
			activeByClickSingle: 1,
			activeByClickDouble: 0,
	{{Lang::locale()}}:
	vdp_translation_{{Lang::locale()}}.js
	},
	components: {
		vuejsDatepicker
	},

	methods: {

		plural: function (key, count) {
			var index = 0;
			if (count > 1) {
				index = 1;
			} else if (count > 4) {
				index = 2;
			}
			if (count == 0) {
				return null;
			}
			return app.pluralData[key][index];
		},

		updateHours: function () {
			for (i = 0; i < this.openHours; i++) {
				this.hours.push({
					value: (this.openHours + i-1) + ':00' ,
					enabled: true
				});
			}

		},

		OrderDisabled: function () {
			if (
					!this.userInfo['name'] ||
					!this.selectedPersons ||
					!this.selectedDate ||
					!this.selectedHour ||
					(!this.userInfo['email'] && !this.userInfo['phone'])
			) {
				return true;
			}
			return false;
		},

		groupRequest: function () {

			if (this.OrderDisabled()) {
				console.log('order still disabled');
				return;
			}
			this.loader = true;
			axios.post('{{ route('placeGroupOrder') }}', {
				name: app.userInfo.name,
				email: app.userInfo.email,
				phone: app.userInfo.phone,
				date: app.customFormatted(app.selectedDate),
				hour: app.selectedHour,
				message: app.userInfo.message,
				persons: app.selectedPersons
			})
				.then(function (response) {
					app.loader = false;
					if (response.data.status == 'success') {
						app.groupRequestSuccess = true;
					}
				});


		},
		customFormatted(date)
		{
			return moment(date).format('DD.MM.YYYY');
		},
		getreservationsCount: function () {
			this.loader = true;
			axios.get('{{ route('getreservationsCount') }}')
					.then(function (response) {
						app.loader = false;
						// checking reserved hours count for each day
						Object.keys(app.disabledHours).forEach(function (key) {
							var day = app.disabledHours[key];
							if (Object.keys(day).length >= app.openHours) {
								app.disabledDates.dates.push(new Date(day));
							}
						});
					});
		}
	},
	mounted: function () {
		this.userInfo = [];
		this.selectedDate = '';
		this.selectedHour = null;
		this.selectedPersons = null;
		this.updateHours();
		this.getreservationsCount();

	},
	watch: {
		selectedPersons: function (val, oldVal) {
			if (val < 0) {
				this.selectedPersons = oldVal;
			}
		}
	}
	});
</script>

@endsection