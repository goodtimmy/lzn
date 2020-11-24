@extends("layouts.app")
@section("content")
@section('head_title', __('Reservation | Beer baths in Prague') )

@include("_particles.vuejs")

<header id="home" class="jumbotron bg-inverse text-center center-vertically jumbotron_reservation">
    <div class="container">
        <h1 class="display-3 m-b-lg animated fadeInDown">{{__('Reservation')}}</h1>
        <a class="btn btn-secondary-outline m-b-md animated fadeInUp slideble" href="#reservation"
           role="button">{{__('Lázně Pramen Letná')}}<br><span>{{__('in Prague')}}</span></a>
        <div class="list-inline social-share animated fadeInUp">
            <a title="{{__('Beer baths in Prague on Tripadvisor')}}"
               href="https://www.tripadvisor.com/Attraction_Review-g274707-d7377900-Reviews-Lazne_Pramen_Beer_and_Wine_spa-Prague_Bohemia.html"
               target="_blank"><img src="{{ URL::asset('assets/img/tripadvisor_white.svg') }}"
                                    alt="{{__('Beer baths in Prague')}}"/><br>
                <span>{{__('Tripadvisor header')}}</span></a>
        </div>
    </div>
</header>

<section id="reservation" class="section_reservation">
    <div id="loader-layout" v-if="loader">
        <img src="{{ URL::to('assets/img/loader.svg') }}">
    </div>
    <div class="container animated fadeInUp">
        <div class="row p-y-lg">
            <div class="col-lg-8 col-md-6 col-sm-12 col-xs-12">
                <div class="section_reservation_inside">
                    <h2>{{__('Reservation beer baths in Prague')}}</h2>
                    <p>{{__('Choose the number of baths and people, date and time of visit, enter your details')}}
                        . {{__('You can make the payment immediately')}}.
                        <span>{{__('The reservation is binding')}}</span>.</p>
                    <form class="beer_baths_reservation_form">
                        <div class="section_reservation_inside_block bath_count_block">
                            <h4>1. {{__('Types and number of bathtubs')}}</h4>
                            <p>{{__('You can choose up to 12 single baths and up to 4 double baths')}}.</p>
                            <div class="row">
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <img
                                            @click="activeByClickChange(1)"
                                            v-bind:class="{ active:  activeByClickSingle == 1}"
                                            src="{{ URL::asset('assets/img/single_bath.jpg') }}"
                                            class="img-responsive" alt=""/>

                                    <select v-model="valB1" class="form-control" id="single_baths_count">
                                        {{--<option value="0" selected>{{__('I dont need single bathtube')}}</option>--}}
                                        @for($i = 0; $i <= 12; $i++)
                                            <option value="{{ $i }}" {{ $i == 0 ? "selected" : ""}}>
                                                {{ $i == 0 ? __('I dont need single bathtube') : trans_choice('n single baths', $i, ['count' => $i]) }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <img
                                            @click="activeByClickChange(2)"
                                            v-bind:class="{ active: activeByClickDouble == 1}"
                                            src="{{ URL::asset('assets/img/double_bath.jpg') }}" class="img-responsive"
                                            alt=""/>

                                    <select v-model="valB2" class="form-control" id="double_baths_count">
                                        {{--<option selected value="0">{{__('I dont need double bathtube')}}</option>--}}
                                        @for($i = 0; $i <= 4; $i++)
                                            <option value="{{ $i }}" {{ $i == 0 ? "selected" : ""}}>
                                                {{ $i == 0 ? __('I dont need double bathtube') : trans_choice('n double baths', $i, ['count' => $i]) }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="section_reservation_inside_block date_time_block">
                            <h4>2. {{__('Date and time')}}</h4>
                            <div class="row">
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <i class="ic fas fa-calendar-alt"></i>
                                        <vuejs-datepicker
                                                v-model="selectedDate"
                                                :format="customFormatted"
                                                :monday-first="true"
                                                class="form-control"
                                                id="date"
                                                :disabled-dates="disabledDates"
                                                :language="{{Lang::locale()}}"
                                        ></vuejs-datepicker>
                                        <label for="date" class="form-label">{{__('Date')}}</label>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <i class="ic fas fa-clock"></i>
                                        <select v-model="selectedHour" class="form-control" id="time" required=""
                                                data-validation-required-message="Choose time" aria-invalid="false">
                                            <option v-for="option in hours" :disabled="!option.enabled">
                                                @{{ option.value }}
                                            </option>
                                        </select>
                                        <label for="time" class="form-label">{{__('Time')}}</label>
                                    </div>
                                </div>
                            </div>
                            <p>
                                <span>{{__('The procedure takes 1 hour')}}</span>. {{__('If you need a longer time, call us at')}}
                                <a href='tel:+420222456789'>+420 222 456 789</a> {{__('or write email to')}} <a
                                        href='mailto:info@beer-baths.com'>info@beer-baths.com</a>.</p>
                        </div>
                        <div class="section_reservation_inside_block personal_data_block">
                            <h4>3. {{__('Your personal info')}}</h4>
                            <p>{{__('Enter your contact details carefully — we will create your reservations according to them')}}.</p>
                            <div class="row">
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="name" class="form-label">
                                            <i class="fas fa-user"></i>
                                            {{__('Name')}}</label>
                                        <input v-model="userInfo['name']" class="form-control" id="name" type="text"
                                               required="" data-validation-required-message="Enter your name"
                                               aria-invalid="false"/>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="surname" class="form-label">
                                            <i class="fas fa-user"></i>
                                            {{__('Surname')}}</label>
                                        <input v-model="userInfo['lastname']" class="form-control" id="surname"
                                               type="text" required=""
                                               data-validation-required-message="Enter your surname"
                                               aria-invalid="false"/>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="email" class="form-label">
                                            <i class="fas fa-envelope"></i>
                                            {{__('Email')}}</label>
                                        <input v-model="userInfo['email']" class="form-control" id="email" type="email"
                                               required="" data-validation-required-message="Enter your email"
                                               aria-invalid="false"/>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="phone" class="form-label">
                                            <i class="fas fa-phone"></i>
                                            {{__('Phone')}}</label>
                                        <input v-model="userInfo['phone']" class="form-control" id="phone" type="tel"
                                               required="" data-validation-required-message="Enter your phone number"
                                               aria-invalid="false"/>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="message" class="form-label">
                                            <i class="fas fa-comment"></i>
                                            {{__('Message')}} <span>({{__('specify your wishes')}})</span></label>
                                        <textarea v-model="userInfo['message']" class="form-control" id="message"
                                                  rows="3" placeholder="" required=""
                                                  data-validation-required-message="Enter your message"
                                                  aria-invalid="false"></textarea>
                                    </div>
                                    <div class="form-check">
                                        <input v-model="userInfo['terms']" type="checkbox" class="form-check-input"
                                               id="beer_baths_conditions">
                                        <label class="form-check-label" for="beer_baths_conditions">
                                            {{__('I accept terms and conditions')}}.
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <div v-on:click="pay" v-bind:class="{ disabled: OrderDisabled() }"
                                             class="summary_block order">
                                            <a class="order_button">
                                                {{__('Order and pay')}}<br/>
                                                <span class="amount" v-if="totalPrice > 0">@{{ totalPrice }} CZK</span>
                                            </a>
                                        </div>
                                        <div class="summary_block credit_cards">
                                            <img src="{{ URL::asset('assets/img/payments/gpwebpay.svg') }}" alt=""/>
                                            <img src="{{ URL::asset('assets/img/payments/visa.svg') }}" alt=""/>
                                            <img src="{{ URL::asset('assets/img/payments/mc.svg') }}" alt=""/>
                                            <img src="{{ URL::asset('assets/img/payments/maestro.svg') }}" alt=""/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 reservation_page_summary">
                <div class="reservation_page_summary_block">
                    <div class="summary_block">
                        <h6>1. {{__('Types and number of bathtubs')}}</h6>
                        <p v-if="valB1 || valB2">
                            <span v-if="valB1">@{{ plural('single_baths', valB1) }}<br/></span>
                            <span v-if="valB2">@{{ plural('double_baths', valB2) }}<br/></span>
                            <span v-if="totalPrice > 0">@{{ totalPrice }} CZK</span>
                        </p>
                    </div>
                    <div class="summary_block">
                        <h6>2. {{__('Date and time')}}</h6>
                        <p v-if="customFormatted(selectedDate).length < 12">@{{ customFormatted(selectedDate) }} — @{{
                            selectedHour }}</p>
                    </div>
                    <div class="summary_block">
                        <h6>3. {{__('Your personal info')}}</h6>
                        <p>@{{ userInfo['name'] }} @{{ userInfo['lastname'] }}<br/>@{{ userInfo['phone'] }}<br/>@{{
                            userInfo['email'] }}</p>
                    </div>
                    <div class="summary_block order">
                        <div class="order_button" v-on:click="pay" v-bind:class="{ disabled: OrderDisabled() }">
                            {{__('Order and pay')}}<br/>
                            <span class="amount" v-if="totalPrice > 0">@{{ totalPrice }} CZK</span>
                        </div>
                    </div>
                    <div class="summary_block credit_cards">
                        <img src="{{ URL::asset('assets/img/payments/gpwebpay.svg') }}" alt=""/>
                        <img src="{{ URL::asset('assets/img/payments/visa.svg') }}" alt=""/>
                        <img src="{{ URL::asset('assets/img/payments/mc.svg') }}" alt=""/>
                        <img src="{{ URL::asset('assets/img/payments/maestro.svg') }}" alt=""/>
                    </div>
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
            hours: [],
            userInfo: [],
            totalPrice: null,
            priceB1: {{env('b1_price', 1600)}},
            priceB2: {{env('b2_price', 2200)}},
            valB1: 0,
            valB2: 0,
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
            activeByClickSingle: 0,
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

        activeByClickChange: function (bcount) {

            if (bcount == 1) {
                if (app.valB1 == 0) {
                    app.valB1 = 1;
                    app.activeByClickSingle = 1;
                } else if (app.valB1 > 0) {
                    app.valB1 = 0;
                    app.activeByClickSingle = 0;
                }

            }
            else if (bcount == 2) {
                if (app.valB2 == 0) {
                    app.valB2 = 1;
                    app.activeByClickDouble = 1;
                } else if (app.valB2 > 0) {
                    app.valB2 = 0;
                    app.activeByClickDouble = 0;
                }

            }
        },

        getreservationsCount: function () {
            app.loader = true;
            axios.get('{{ route('getreservationsCount') }}')
                .then(function (response) {
                    app.loader = false;
                    var requireB1 = app.maxB1 - app.valB1;
                    var requireB2 = app.maxB2 - app.valB2;
                    response.data.b1.forEach(hour => {
                        if (hour.c > requireB1) {
                            var day = hour.d.split(" ");
                            if (typeof app.disabledHours[day[0]] === 'undefined') {
                                app.disabledHours[day[0]] = [];
                            }
                            app.disabledHours[day[0]][day[1]] = true;
                        }
                    });
                    response.data.b2.forEach(hour => {
                        if (hour.c > requireB2) {
                            var day = hour.d.split(" ");
                            if (typeof app.disabledHours[day[0]] === 'undefined') {
                                app.disabledHours[day[0]] = [];
                            }
                            app.disabledHours[day[0]][day[1]] = true;
                        }
                    });
                    // checking reserved hours count for each day
                    Object.keys(app.disabledHours).forEach(function (key) {
                        var day = app.disabledHours[key];
                        if (Object.keys(day).length >= app.openHours) {
                            app.disabledDates.dates.push(new Date(day));
                        }
                    });
                });
        }
    ,
        updateHours: function () {
            if (this.selectedDate == '') {
                return;
            }
            app.loader = true;
            axios.post('{{ route('getHourList') }}', {
                date: this.customFormatted(this.selectedDate),
                b1: this.valB1,
                b2: this.valB2
            })
                .then(function (response) {
                    app.loader = false;
                    app.hours = [];
                    response.data.forEach(hour => {
                        app.hours.push({
                            value: hour.value,
                            enabled: hour.enabled
                        });
                    });
                });
        }
    ,
        updatePrice: function () {
            app.totalPrice = null;
            if (app.valB1 != null && app.valB2 != null) {
                app.totalPrice = app.valB1 * app.priceB1 + app.valB2 * app.priceB2;
            } else if (app.valB1 != undefined) {
                app.totalPrice = app.valB1 * app.priceB1;
            } else if (app.valB2 != undefined) {
                app.totalPrice = app.valB2 * app.priceB2;
            }
        }
    ,
        OrderDisabled: function () {
            if (
                this.totalPrice == 0 ||
                !this.userInfo['terms'] ||
                !this.userInfo['name'] ||
                !this.selectedDate ||
                !this.selectedHour ||
                (!this.userInfo['email'] && !this.userInfo['phone'])
            ) {
                return true;
            }
            return false;
        }
    ,
        pay: function () {
            if (this.OrderDisabled()) {
                console.log('order still disabled');
                return;
            }
            var form = document.createElement('form');
            document.body.appendChild(form);
            form.method = 'post';
            form.action = '{{ route('pay') }}';
            var fields = {
                amount: app.totalPrice,
                name: app.userInfo.name,
                lastname: app.userInfo.lastname,
                email: app.userInfo.email,
                phone: app.userInfo.phone,
                date: app.customFormatted(app.selectedDate),
                hour: app.selectedHour,
                message: app.userInfo.message,
                b1Conut: app.valB1,
                b2Conut: app.valB2,
            };
            for (var field in fields) {
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = field;
                input.value = fields[field];
                form.appendChild(input);
            }
            form.submit();
        }
    ,
        customFormatted(date)
        {
            return moment(date).format('DD.MM.YYYY');
        }
    }
    ,
    mounted: function () {
        this.userInfo = [];
        this.hours = [];
        this.selectedDate = '';
        this.selectedHour = null;

		},
		watch: {
			selectedDate: function (val, oldVal) {
				app.updateHours();
			},
			selectedHour: function (val, oldVal) {
				console.log(val);
			},
			valB1: function (val, oldVal) {
				// cleaning date and time
				if (oldVal < val) {
					app.selectedDate = '';
					app.selectedHour = null;
					app.hours = [];
				}
				app.updatePrice();
				app.getreservationsCount();
			},
			valB2: function (val, oldVal) {
				// cleaning date and time
				if (oldVal < val) {
					app.selectedDate = '';
					app.selectedHour = null;
					app.hours = [];
				}
				app.updatePrice();
				app.getreservationsCount();
			}
	}
	});
</script>
@endsection