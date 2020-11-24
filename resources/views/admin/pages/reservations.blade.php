@extends("admin.admin_app")
@section("content")
    <script src="{{ URL::asset('assets/js/moment.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/vuejs-datepicker.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/datepicker-ru.js') }}"></script>
    <script src="{{ URL::asset('assets/js/Sortable.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/vuedraggable.umd.min.js') }}"></script>

    <div id="main" class="vue">
        <div id="loader-layout" v-if="loader">
            <img src="{{ URL::to('assets/img/loader.svg') }}">
        </div>
        <div class="page-header">
            <div class="pull-right">

                <a v-if="!showGroupOfReservation" @click="openModalToCreateReservation(0)" class="btn btn-primary">
                    {{__('New reservation')}} <i class="fa fa-plus"></i>
                </a>

                <a @click="listOrBlockOrGroup(3)" class="btn btn-primary">
                    {{__('Group reservations list')}}
                    <span v-if="unsortedGroups > 0" class="active red">@{{ unsortedGroups }}</span>
                </a>

                <a @click="listOrBlockOrGroup" class="btn btn-primary" v-if="!showListOfReservation">
                    {{__('Reservations list')}}
                    <span v-if="unsortedAll > 0" class="active red">@{{ unsortedAll }}</span>
                </a>

                <a @click="listOrBlockOrGroup" class="btn btn-primary" v-if="showListOfReservation">
                    {{__('Reservations on scheme')}}
                    <span v-if="unsortedAll > 0" class="active red">@{{ unsortedAll }}</span>
                </a>
            </div>

            <h4 v-if="!showListOfReservation && !showGroupOfReservation">{{__('Reservations on scheme')}}</h4>
            <h4 v-if="showListOfReservation">{{__('Reservations list')}}</h4>
            <h4 v-if="showGroupOfReservation">{{__('Group reservations list')}}</h4>


        </div>
        @if(Session::has('flash_message'))
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                {{ Session::get('flash_message') }}
            </div>
    @endif

    <!-- datepicker -->
        <div class="panel panel-default col-lg-12" style="padding:5px;"
             v-if="!showListOfReservation && !showGroupOfReservation">
            <div class="row">
                <div class="col-md-2 col-sm-4 date_time_block date_block_search">
                    <div class="form-control" style="padding:0;">
                        <i class="ic fa fa-calendar"></i>
                        <vuejs-datepicker
                                v-model="selectedDate"
                                :format="customFormatted"
                                :monday-first="true"
                        ></vuejs-datepicker>
                    </div>
                </div>
                <div class="col-md-10 col-sm-8">
                    <ul id="hourPick" style="display:block;">
                        <li v-for="(n, i) in openHours"
                            @click="updatedTime(i+openFrom)"
                            v-bind:class="{ active: selectedHour == i+openFrom }"
                        >
                            @{{ i+openFrom }}:00
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- reservation list  - all unsorted orders -->
    {{--<div class="panel panel-default col-lg-12" style="padding:5px;" v-if="showListOfReservation">--}}
    {{--<div class="row">--}}
    {{--<div class="col-md-12 col-sm-12">--}}
    {{--{{__('Unsorted reservations')}}: --}}
    {{--<span class="label label-default">@{{ unsortedAll }} </span>--}}

    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}

    <!-- reservation groups  - all group orders -->

        <!-- new reservation block -->
        <div class="panel panel-default col-lg-12 col-md-12 col-sm-12"
             v-if="!showListOfReservation && !showGroupOfReservation">
            <h4>{{__('New reservations')}}</h4>
            <draggable v-model="unsortedOrders" @end="moved" :move="checkMove" :options='{group: "orders"}'
                       class="unsorted">
                <div
                        v-bind:class="{ orderWithParent: element.parent_id }"
                        class="order"
                        v-for="element in unsortedOrders"
                        :key="element.order_id"
                        :data-capacity="element.bath_capacity"
                >
                    <p>@{{element.name}}</p>
                    <i v-for="i in element.bath_capacity" class="fa fa-male"></i>
                    <i v-if="element.reservation_approved" class="fa fa-check-circle-o" data-toggle="tooltip"
                       data-placement="top" title="{{__('Confirmed')}}"></i>
                    <i v-if="element.reservation_paid" class="fa fa-credit-card" data-toggle="tooltip"
                       data-placement="top" title="{{__('Paid')}}"></i>
                    <i class="res_number_small" v-if="!element.parent_id">№ @{{ element.order_id}}</i>
                    <i class="res_number_small" v-if="element.parent_id">№ @{{ element.parent_id}}</i>
                    <i v-if="!element.parent_id" @click="openModalToEditReservation(element)" class="fa fa-pencil"
                       aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{__('Edit')}}"></i>
                </div>
            </draggable>
        </div>

        <!-- rooms -->
        <div class="" v-if="!showListOfReservation && !showGroupOfReservation">
            <div class="row baths_row baths_row_5">

                <div class="col-sm-2"
                     v-for="room in rooms"
                     v-bind:class="dynamicClass(room.id)"
                >
                    <div class="bath_room">
                        <div class="room_name_block">@{{ room.name }}</div>

                        <ul class="baths_list">
                            <li class="bath_square"
                                v-bind:id="dynamicId(bathIndex)"
                                v-for="(bath, bathIndex) in baths"
                                v-if="bathsData[bathIndex].room_id == room.id">

                                <i v-for="i in bathsData[bathIndex].capacity" class="fa fa-male"></i>
                                <span>
                    @{{ bathsData[bathIndex].name }}
                </span>
                                <draggable
                                        v-model="baths[bathIndex]"
                                        class="bathList"
                                        @end="moved"
                                        :move="checkMove"
                                        :options='{group: "orders"}'
                                        :data-capacity="bathsData[bathIndex].capacity"
                                >
                                    <div v-bind:class="{ orderWithParent: element.parent_id }" class="order"
                                         v-for="element in baths[bathIndex]" :key="element.id"
                                         :data-capacity="element.bath_capacity">
                                        <p>@{{element.name}}</p>
                                        <i v-for="i in element.bath_capacity" class="fa fa-male"></i>
                                        <i v-if="element.reservation_approved" class="fa fa-check-circle-o"
                                           data-toggle="tooltip"
                                           data-placement="top" title="{{__('Confirmed')}}"></i>
                                        <i v-if="element.reservation_paid" class="fa fa-credit-card"
                                           data-toggle="tooltip"
                                           data-placement="top" title="{{__('Paid')}}"></i>
                                        <i class="res_number_small" v-if="!element.parent_id">№ @{{
                                            element.order_id}}</i>
                                        <i class="res_number_small" v-if="element.parent_id">№ @{{
                                            element.parent_id}}</i>
                                        <i v-if="!element.parent_id" @click="openModalToEditReservation(element)"
                                           class="fa fa-pencil" aria-hidden="true" data-toggle="tooltip"
                                           data-placement="top"
                                           title="{{__('Edit')}}"></i>
                                    </div>
                                </draggable>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- reservation list -->
        <div class="panel panel-default col-lg-12" style="padding:5px;" v-if="showListOfReservation">

            <!-- tables -->

            <div class="row">
                <div class="col-lg-12">

                    <div class="unsorted"> <!-- v-model="reservationList" :options='{group: "id"}' -->

                        <div class="table-responsive">
                            <table class="table table-striped reservationTable">
                                <thead>
                                <tr>
                                    <th>№</th>
                                    <th>{{__('Status')}}</th>
                                    <th>{{__('Name')}} / {{__('Company name and VAT')}}</th>
                                    <th>{{__('Contact')}}</th>
                                    <th>{{__('Date')}}</th>
                                    <th>{{__('Time')}}</th>
                                    <th>{{__('Baths')}}</th>
                                    <th>{{__('Price')}}</th>
                                    <th>{{__('Created')}}</th>
                                    <th>{{__('Comment')}}</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>

                                <tr v-for="element in reservationList" :key="element.id">
                                    <td>@{{element.id}}</td>
                                    <td>
                                        <span class="badge badge-light">
                                            <i v-if="element.paid" class="fa fa-credit-card" data-toggle="tooltip"
                                               data-placement="top" title="{{__('Paid')}}"></i>
                                        </span>
                                        <span class="badge badge-info badge-pill">
                                            <i v-if="element.approved" class="fa fa-check-circle-o"
                                               data-toggle="tooltip"
                                               data-placement="top" title="{{__('Confirmed')}}"></i>
                                        </span>
                                    </td>
                                    <td v-if="!element.company_name">@{{element.name}}</td>
                                    <td v-else >@{{element.company_name}}
                                        <br>
                                        @{{element.vat_number}}
                                        </td>
                                    <td><td>@{{ element.phone }}<br>@{{ element.email }}</td>
                                    <td>@{{ element.start_date }}</td>
                                    <td class="cursor_pointer"
                                        @click="goToThisTime(element.start_date,element.start_time)"
                                    >@{{ element.start_time }}
                                    </td>
                                    <td>@{{ element.number_of_single_baths }}&nbsp;одноместных<br>@{{
                                        element.number_of_double_baths }}&nbsp;двухместная
                                    </td>
                                    <td>@{{ element.number_of_single_baths * b1_price + element.number_of_double_baths *
                                        b2_price }} Kč
                                    </td>
                                    <td>@{{ element.created_at_date }}<br>@{{ element.created_at_time }}</td>
                                    <td>@{{ element.comment }}</td>
                                    <td>
                                        <span class="badge badge-info badge-pill pointer">
                                            <i v-if="!element.parent_id"
                                               @click="openModalToEditReservation(element)"
                                               class="fa fa-pencil"
                                               aria-hidden="true" data-toggle="tooltip"
                                               data-placement="top"
                                               title="{{__('Edit')}}"></i>
                                        </span>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div class="row">
                <div class="col-lg-12">
                    <ul class="pagination">
                        <li class="page-item">
                            <a class="page-link" href="#"
                               @click="changePage(-1)"
                            >{{__('Previous')}}</a>
                        </li>

                        <li class="page-item"
                            v-for="n in numberOfPages"
                            v-bind:class="{ active: selectedPage ==  n}"
                        >
                            <a class="page-link" href="#"
                               v-if="n <2 || n == numberOfPages || n == selectedPage || n == selectedPage +1 || n == selectedPage -1 || n == selectedPage +10 || n == selectedPage -10 || n == selectedPage +50 || n == selectedPage -50 || n == selectedPage +100 || n == selectedPage -100"
                               @click="pageClick(n)"
                            > @{{ n }}</a>
                            <span v-else-if="(n == 2 && selectedPage != 2) || (n == (numberOfPages - 1) && selectedPage != (numberOfPages - 1))">.</span>
                        </li>

                        <li class="page-item">
                            <a class="page-link" href="#"
                               @click="changePage(1)"
                            >{{__('Next')}}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- group reservations -->

        <div class="panel panel-default col-lg-12" style="padding:5px;" v-if="showGroupOfReservation">

            <!-- tables -->

            <div class="row">
                <div class="col-lg-12">

                    <div class="unsorted">

                        <div class="table-responsive">
                            <table class="table table-striped reservationTable">
                                <thead>
                                <tr>
                                    <th>№</th>
                                    <th>{{__('Name')}}</th>
                                    {{--<th>{{__('Name')}}</th>--}}
                                    <th>{{__('Email')}}</th>
                                    <th>{{__('Phone')}}</th>
                                    <th>{{__('Bath needed')}}</th>
                                    <th>{{__('Start')}}</th>
                                    <th>{{__('End')}}</th>
                                    <th>{{__('Processed')}}</th>

                                </tr>
                                </thead>
                                <tbody>

                                <tr v-for="element in groupOrderList" :key="element.id">
                                    <td>@{{ element.id}}</td>
                                    <td>@{{ element.name}}</td>
                                    <td>@{{ element.email }}</td>
                                    <td>@{{ element.phone }}</td>
                                    <td>@{{ element.bath_needed }}</td>
                                    <td>@{{ element.start_at }}</td>
                                    <td>@{{ element.end_at }}</td>
                                    <td>
                                        <button v-if="element.processed" type="button"
                                                class="btn btn-success btn-block btn-primary btn-sm" disabled>
                                            <i class="fa fa-check-circle-o"
                                               data-toggle="tooltip"
                                               data-placement="top" title="{{__('Processed')}}"></i>
                                        </button>

                                        <button v-else="!element.rprocessed" type="button"
                                                class="btn btn-danger btn-block btn-primary btn-sm"
                                                @click="processGroupOrder(element.id)">

                                            <i class="fa fa-minus"
                                               data-toggle="tooltip"
                                               data-placement="top" title="{{__('Confirmed')}}"></i>
                                        </button>

                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div class="row">
                <div class="col-lg-12">
                    <ul class="pagination">
                        <li class="page-item">
                            <a class="page-link" href="#"
                               @click="changePage(-1)"
                            >{{__('Previous')}}</a>
                        </li>

                        <li class="page-item"
                            v-for="n in numberOfPages"
                            v-bind:class="{ active: selectedPage ==  n}"
                        >
                            <a class="page-link" href="#"
                               v-if="n <2 || n == numberOfPages || n == selectedPage || n == selectedPage +1 || n == selectedPage -1 || n == selectedPage +10 || n == selectedPage -10 || n == selectedPage +50 || n == selectedPage -50 || n == selectedPage +100 || n == selectedPage -100"
                               @click="pageClick(n)"
                            > @{{ n }}</a>
                            <span v-else-if="(n == 2 && selectedPage != 2) || (n == (numberOfPages - 1) && selectedPage != (numberOfPages - 1))">.</span>
                        </li>

                        <li class="page-item">
                            <a class="page-link" href="#"
                               @click="changePage(1)"
                            >{{__('Next')}}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- modal -->
        <div id="my-modal" class="modal modal-lg fade">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content" style="padding: 20px; color: #4f4f4f;">

                    <div class="modal-header">
                        <h4 v-if="modalData.id != 0" class="modal-title">{{__('Reservation')}} № @{{ modalData.id}}</h4>
                        <h4 v-else>{{__('New reservation')}}</h4>
                        <h6 class="creation_date_text">{{__('Created')}}: @{{ customFormatted(modalData.created_at) }}</h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </h5>
                    </div>

                    <div class="modal-body">
                        <div class="form-group">
                            <label v-if="modalData.id" for="userdataEdit"> {{__('Edit')}}</label>
                            <input v-if="modalData.id"
                                   type="checkbox"
                                   id="userdataEdit"
                                   v-model="modalData.userfields_active">

                            <input type="text" class="form-control"
                                   placeholder="{{__('Full name')}}"
                                   v-model="modalData.name"
                                   :disabled="!modalData.userfields_active">
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <input v-model="modalData.email"
                                           class="form-control"
                                           type="email"
                                           placeholder="Email"
                                           :disabled="!modalData.userfields_active"/>
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <input v-model="modalData.phone"
                                           class="form-control"
                                           placeholder="{{__('Phone')}}"
                                           :disabled="!modalData.userfields_active"/>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="isCompany">{{__('Company')}}</label>
                            <input
                                   type="checkbox"
                                   id="isCompany"
                                   v-model="modalData.isCompany">

                            <div class="row">
                                <div class="col-md-8 col-sm-12 col-xs-12">
                                    <input v-model="modalData.companyName"
                                           class="form-control"
                                           type="text"
                                           placeholder="{{__('Company name')}}"
                                           :disabled="!modalData.isCompany"/>
                                </div>
                                <div class="col-md-4 col-sm-12 col-xs-12">
                                    <input v-model="modalData.vat"
                                           class="form-control"
                                           placeholder="VAT/IČO"
                                           :disabled="!modalData.isCompany"/>
                                </div>
                            </div>
                        </div>

                        <div class="col">

                            <div class="form-group">
                            <textarea v-model="modalData.comment"
                                      class="form-control"
                                      rows="2"
                                      placeholder="{{__('Comment')}}"
                            ></textarea>
                            </div>
                        </div>

                        <!-- baths needed if modal is being opened via from group reservation list-->
                        <div class="section_reservation_inside_block"
                             v-if="showGroupOfReservation">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <h5>{{__('Persons count')}}: @{{ modalData.person_count }} </h5>
                                </div>
                            </div>
                        </div>

                        <!-- baths -->
                        <div class="section_reservation_inside_block bath_count_block">
                            <h5>{{__('Types and number of bathtubs')}}</h5>
                            <div class="row">
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <select v-model="modalData.valB1" class="form-control"
                                            placeholder="одноместные ванны" id="single_baths_count"
                                            :disabled="modalData.id != 0">

                                        <option
                                                v-for="(bath, index) in singleBaths"
                                                v-bind:value="index+1"
                                        >
                                            @{{ index +1 }} одноместных
                                        </option>
                                        <option value="0">не нужно</option>
                                    </select>

                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <select v-model="modalData.valB2" class="form-control"
                                            placeholder="двухместные ванны" id="double_baths_count"
                                            :disabled="modalData.id != 0">

                                        <option
                                                v-for="(bath, index) in doubleBaths"
                                                v-bind:value="index+1"
                                        >
                                            @{{ index +1 }} двухместных
                                        </option>
                                        <option value="0">не нужно</option>

                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- bath select -->
                        <div class="section_reservation_inside_block"
                             v-if="modalData.id != 0 && showListOfReservation">
                            <h5>{{__('Bath number')}}</h5>
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <select v-model="modalData.bath_id" class="form-control">
                                        <option value="0">ванна не выбрана</option>
                                        <option
                                                v-for="(bath, bathIndex) in baths"
                                                v-bind:value="bathIndex+1"
                                                {{--:disabled="checkIfRoomBusy(bathsData[bathIndex].room_id)"--}}
                                        >
                                            ванна № @{{ bathIndex +1 }} @{{ bathsData[bathIndex].name }}

                                        </option>
                                    </select>

                                </div>
                            </div>
                        </div>

                        <br/>

                        <!-- child reservations -->
                        <div v-if="showListOfReservation && modalData.id != 0">
                            <div
                                    v-for="element in listForModal"
                                    @click="openModalToEditReservation(element)"
                                    v-bind:class="{ active_reserv: element.id == modalData.id, main_reserv: !element.parent_id}"
                                    {{--v-bind:class="{ main_reserv: !element.parent_id}"--}}
                                    class="order"
                                    :key="element.id"
                                    :data-capacity="element.bath_capacity"
                            >
                                <p>
                                    <span v-if="!element.parent_id"
                                          style="text-transform: uppercase;">@{{element.name}}</span>
                                    <span v-else> @{{element.name}}</span>
                                </p>
                                <i v-for="i in element.bath_capacity" class="fa fa-male"></i>

                                <i class="res_number_small">№ @{{ element.order_id}}</i>
                                {{--<i class="res_number_small" v-if="element.parent_id">№ @{{ element.parent_id}}</i>--}}

                            </div>
                        </div>

                        <br/>

                        <div class="section_reservation_inside_block date_time_block">
                            <div class="row">
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <i class="ic fa fa-calendar"></i>
                                        <vuejs-datepicker
                                                v-model="modalData.start_at"
                                                class="form-control"
                                                id="date"
                                                :format="customFormatted"
                                                :monday-first="true"
                                                :disabled-dates="disabledDates"
                                                :language="ru"
                                        ></vuejs-datepicker>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <i class="ic fa fa-clock-o"></i>
                                        <select v-model="modalData.selectedHour"
                                                class="form-control" id="time" required=""
                                                data-validation-required-message="Выберете время" aria-invalid="false">
                                            <option v-for="hour in hours"
                                                    v-bind:value="hour.value"
                                                    :disabled="!hour.enabled">
                                                @{{ hour.text }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{--<select v-model="selectedHour" class="form-control" id="time" required="" data-validation-required-message="Choose time" aria-invalid="false">--}}

                        {{--</select>--}}

                        <div class="form-check">
                            <input type="checkbox"
                                   class="form-check-input"
                                   v-model="modalData.approved"
                                   id="approvedCheckbox"
                            >
                            <label for="approvedCheckbox"> {{__('Reservation is confirmed')}}</label>
                        </div>

                        <div class="form-check">
                            <input type="checkbox"
                                   class="form-check-input"
                                   v-model="modalData.paid"
                                   id="paidCheckbox"
                            >
                            <label for="paidCheckbox">{{__('Reservation is paid')}}</label>
                        </div>

                        <div class="section_reservation_inside_block"
                             v-if="showGroupOfReservation && !modalData.paid">
                            <div class="row">
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <select v-model="modalData.payment_type"
                                            class="form-control" required=""
                                            data-validation-required-message="{{_('Payment type')}}" aria-invalid="false">
                                        <option value="cash">{{__('Cash')}}</option>
                                        <option value="card">{{__('Payment card')}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <span class="amount" v-if="totalPrice > 0">@{{ totalPrice }} CZK</span>
                    </div>
                    <div class="modal-footer">
                        <div class="pull-left">
                            <button v-if="modalData.id != 0" type="button" class="btn btn-danger"
                                    @click="deleteEditedReservation"><i class="ic fa fa-trash"></i> Удалить заявку
                            </button>
                        </div>
                        <button type="button" class="btn btn-primary" @click="saveCreatedOrEditedReservation">
                            {{__('Save')}}
                        </button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Close')}}</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>

        var app = new Vue({
            el: '#main',
            data: {
                loader: false,
                message: null,
                hasError: false,
                showNewPostModal: false,
                today: new Date,
                selectedDate: new Date,
                selectedHour: null,
                hours: [
                    {text: '11:00', value: '11:00', enabled: true},
                    {text: '12:00', value: '12:00', enabled: true},
                    {text: '13:00', value: '13:00', enabled: true},
                    {text: '14:00', value: '14:00', enabled: true},
                    {text: '15:00', value: '15:00', enabled: true},
                    {text: '16:00', value: '16:00', enabled: true},
                    {text: '17:00', value: '17:00', enabled: true},
                    {text: '18:00', value: '18:00', enabled: true},
                    {text: '19:00', value: '19:00', enabled: true},
                    {text: '20:00', value: '20:00', enabled: true},
                    {text: '21:00', value: '21:00', enabled: true}

                ],
                openHours: {{ abs( env('open_period_from', 11) - env('open_period_till', 21) )+1 }},
                openFrom: {{ env('open_period_from', 11) }},
                baths: [],
                b1_price: {{ env('b1_price', 1600) }},
                b2_price: {{ env('b2_price', 2200) }},
                singleBaths: [],
                doubleBaths: [],
                bathsData: [],
                bathsOrginal: [],
                bathsCount: 14,
                rooms: [],
                busyRooms: [],
                unsortedOrders: [],

                // reservation list vars
                showListOfReservation: false,
                showGroupOfReservation: false,
                reservationList: [],
                groupOrderList: [],
                numberOfPages: null,
                selectedPage: 1,
                itemsPerPage: {{ env('RESERVATION_LIST_ITEMS_PER_PAGE', 50) }},
                listForModal: [],
                unsortedAll: 0,
                unsortedGroups: 0,

                // modal data
                modalData: {
                    'id': 0,
                    'user_id': 0,
                    'name': '',
                    'phone': '',
                    'email': '',
                    'userfields_active': false,
                    'approved': false,
                    'paid': false,
                    'valB1': null,
                    'valB2': null,
                    'selectedDate': '',
                    'selectedHour': '',
                    'start_at': '',
                    'start_at_old': '',
                    'order_time': [],
                    'comment': '',
                    'groupOrder': 0,
                    'payment_type': '',
                    'person_count': 0,
                    'isCompany': 0,
                    'companyName': null,
                    'vat': null
                },
                disabledDates: {
                    to: new Date(Date.now() - 172800),
                    {{--to: new Date('{{now()}}'), // Disable all dates up to specific date (till today)--}}
                    {{--from: new Date('{{ date('Y-m-d', strtotime('+1 year')) }}'), // Disable all dates after specific date (from next year)--}}
                },
                disabledHours: {},
                valB1: null,
                valB2: null,
                totalPrice: null,
                ru: vdp_translation_ru.js,
            },
            components: {
                vuejsDatepicker,
                vuedraggable
            },
            methods: {

                processGroupOrder: function (id) {
                    app.openModalToCreateReservation(id);
                },

                // reservation list functions
                listOrBlockOrGroup: function (group = 1) {

                    if (group == 3 && !app.showGroupOfReservation) {
                        app.showListOfReservation = 0;
                        app.showGroupOfReservation = 1;
                        app.getGroupList();
                    } else if (app.showListOfReservation) {
                        app.showListOfReservation = 0;
                        app.showGroupOfReservation = 0;
                    } else {
                        app.getReservationList();
                        app.showListOfReservation = 1;
                        app.showGroupOfReservation = 0;
                    }


                },
                changePage: function (e) {

                    if (app.selectedPage + e < 1 || app.selectedPage + e > app.numberOfPages) {
                    } else {
                        app.selectedPage += e;
                        app.getReservationList();
                    }
                },
                pageClick: function (e) {

                    app.selectedPage = e;
                    app.getReservationList();

                },
                //
                dynamicClass: function (id) {
                    return "room_number_" + id;
                },
                dynamicId: function (index) {

                    if (index.toString().length > 1) {
                        return "bath_" + index;
                    } else {
                        return "bath_0" + index;
                    }
                },
                updateHours: function () {
                    if (this.modalData.start_at == '') {
                        return;
                    }
                    app.loader = true;
                    axios.post('{{ route('adminGetHourListAdmin') }}', {
                        date: this.customFormatted(this.modalData.start_at),
                        b1: this.valB1,
                        b2: this.valB2
                    })
                        .then(function (response) {
                            app.loader = false;
                            app.hours = [];
                            response.data.forEach(hour => {
                                app.modalData.order_time.push({
                                    value: hour.value,
                                    enabled: hour.enabled
                                });
                            });
                        });
                },
                //
                deleteEditedReservation: function () {

                    if (confirm("Вы действительно хотите удалить заявку?")) {
                        app.loader = true;
                        axios.post('{{ route('adminDeleteReservation') }}', {
                            id: app.modalData.id,
                        })
                            .then(function (response) {
                                app.loader = false;
                                app.getReservations();
                                app.getReservationList();
                                app.getGroupList();
                            });

                        $("#my-modal").modal('hide');
                    }
                },

                openModalToCreateReservation: function (id) {

                    app.modalData = {};
                    app.modalData.id = 0;
                    app.modalData.userfields_active = true;
                    app.modalData.valB1 = 1;
                    app.modalData.valB2 = 1;
                    app.disabledDates = {
                        to: new Date(Date.now() - 172800),
                    };

                    if (id) {

                        this.loader = true;
                        axios.post('<?php echo e(route('adminGetGroupOrder')); ?>', {
                            id: id,
                        })
                            .then(function (response) {
                                app.loader = false;
                                if (response.data.status == 'success') {

                                    app.modalData.groupOrder = id;

                                    app.modalData.name = response.data.name;
                                    app.modalData.phone = response.data.phone;
                                    app.modalData.email = response.data.email;

                                    app.modalData.valB1 = 0;
                                    app.modalData.valB2 = 0;

                                    app.modalData.person_count = response.data.person_count;

                                    app.modalData.start_at = response.data.start_at;
                                    app.modalData.selectedHour = response.data.selectedHour;
                                }
                            });
                    }

                    // app.updateHours();

                    $("#my-modal").modal('show');
                },
                openModalToEditReservation: function (value) {

                    // crutch (do not touch) - when opening a reservation from list
                    if (!value.order_id) {
                        value.order_id = value.id;
                    }
                    // end of the crutch

                    app.disabledDates = {};

                    app.loader = true;
                    axios.post('{{ route('adminGetReservationToEdit') }}', {
                        order_id: value.order_id,
                    })
                        .then(function (response) {
                            console.log(response.data);
                            app.modalData = response.data[0];
                            if(response.data[0]['company_name']) {
                                app.modalData.isCompany = 1;
                                app.modalData.companyName = response.data[0]['company_name'];
                                app.modalData.vat = response.data[0]['vat_number'];
                            }
                            app.modalData.start_at_old = response.data[0]['start_at'];

                            if (response.data[0]['parent_id'] == null) {
                                app.listForModal = [];
                                for (var i = 0; i < response.data.length; i++) {
                                    if (response.data[i]['bath_id'] == null) {
                                        response.data[i]['bath_id'] = 0;
                                    }
                                    app.listForModal.push({
                                        'id': response.data[i]['id'],
                                        'order_id': response.data[i]['id'],
                                        'bath_capacity': response.data[i]['bath_capacity'],
                                        'parent_id': response.data[i]['parent_id'],
                                        'name': response.data[i]['name'],
                                        "bath_id": response.data[i]['bath_id'],
                                        "companyName": response.data[i]['company_name'],
                                        "vat": response.data[i]['vat_number']
                                    });
                                }
                            } else {

                                if (response.data[0]['bath_id'] == null) {
                                    response.data[0]['bath_id'] = 0;
                                }

                                if (response.data[0]['bath_capacity'] == 1) {
                                    app.modalData.valB1 = 1;
                                    app.modalData.valB2 = 0;
                                } else {
                                    app.modalData.valB1 = 0;
                                    app.modalData.valB2 = 1;
                                }
                            }

                            app.findBusyRooms(response.data[0]['start_at']);

                            app.loader = false;
                        });

                    $("#my-modal").modal('show');

                },
                findBusyRooms: function (date) {

                    axios.post('{{ route('adminGetBusyRooms') }}', {
                        date: date,
                    })
                        .then(function (response) {
                            app.busyRooms = response.data;
                        });
                },
                checkIfRoomBusy: function (id) {

                    var response = false;

                    app.busyRooms.forEach(room_id => {

                        if (room_id == id) {

                            console.log(id + " busy room ");

                            response = true;
                        }
                    });
                    return response;
                },
                saveCreatedOrEditedReservation: function () {
                    app.loader = true;

                    axios.post('{{ route('adminSaveCreatedOrEditedReservation') }}', {
                        order_array: app.modalData,
                    })
                        .then(function (response) {
                            app.loader = false;
                            app.getReservations();
                            app.getReservationList();
                            app.getGroupList();
                        });

                    $("#my-modal").modal('hide');

                },
                //
                moved: function (e) {
                    this.setReservation();
                },
                checkMove: function (e) {

                    if (e.to.classList.value.indexOf('bathList') !== -1) {
                        // disable if capacity not equal
                        if (e.to.attributes['data-capacity'].value > 0 &&
                            e.to.attributes['data-capacity'].value < e.dragged.attributes['data-capacity'].value
                        ) {
                            return false;
                        }
                        // disabling second reservation for single bath
                        if (e.to.childElementCount > 0) {
                            return false;
                        }
                    }
                    return true;
                },
                updatedTime: function (value) {
                    app.selectedHour = value;
                },
                // date and time from sql to ui
                customFormatted(date) {
                    return moment(date).format('DD.MM.YYYY');
                },
                timeFromSqlDatetime(date) {
                    return moment(date).format('HH:mm');
                },
                customDateToSql(date) {
                    var arr = date.split('.');
                    return (arr[2] + "-" + arr[1] + "-" + arr[0]);
                },
                //
                getBathsList: function () {
                    this.loader = true;
                    axios.get('{{ route('adminGetList') }}')
                        .then(function (response) {
                            app.loader = false;
                            app.bathsData = response.data;
                            response.data.forEach(bath => {
                                app.baths.push([]);

                                if (bath.capacity == 2) {
                                    app.doubleBaths.push(bath);
                                } else {
                                    app.singleBaths.push(bath);
                                }

                            });
                            // cloning array
                            app.bathsOrginal = JSON.parse(JSON.stringify(app.baths));
                        });
                },
                getRoomList: function () {
                    this.loader = true;
                    axios.get('{{ route('adminGetRooms') }}')
                        .then(function (response) {
                            app.loader = false;
                            app.rooms = [];
                            // app.bathsData = response.data;
                            response.data.forEach(room => {
                                app.rooms.push({
                                    id: room.id,
                                    name: room.name
                                });
                            });
                        });

                },
                getReservations: function () {
                    this.loader = true;
                    axios.post('{{ route('adminGetReservations') }}', {
                        hour: this.selectedHour,
                        date: this.customFormatted(this.selectedDate)
                    })
                        .then(function (response) {
                            app.loader = false;
                            if (response.data.status == 'success') {

                                app.unsortedOrders = [];
                                if (response.data.unsorted !== undefined) {
                                    response.data.unsorted.forEach(unsorted => {
                                        app.unsortedOrders.push(
                                            {
                                                "name": unsorted.name,
                                                "order_id": unsorted.id,
                                                "fixed": false,
                                                "bath_capacity": unsorted.bath_capacity,
                                                "reservation_paid": unsorted.paid,
                                                "reservation_approved": unsorted.approved,
                                                "parent_id": unsorted.parent_id,
                                            }
                                        )
                                    });
                                }
                                app.unsortedAll = response.data.unsorted_all;

                                // cleaning baths by cloning array
                                app.baths = JSON.parse(JSON.stringify(app.bathsOrginal));

                                if (response.data.sorted !== undefined) {
                                    response.data.sorted.forEach(sorted => {
                                        // alert (sorted.bath_id);
                                        app.baths[sorted.bath_id - 1].push(
                                            {
                                                "name": sorted.name,
                                                "order_id": sorted.id,
                                                "fixed": false,
                                                "bath_capacity": sorted.bath_capacity,
                                                "reservation_paid": sorted.paid,
                                                "reservation_approved": sorted.approved,
                                                "parent_id": sorted.parent_id
                                            }
                                        )
                                    });
                                }
                                Vue.nextTick(function () {
                                    $('[data-toggle="tooltip"]').tooltip();
                                })
                            }
                        });
                },
                getReservationList: function () {
                    this.loader = true;
                    // alert(this.itemsPerPage);
                    axios.post('{{ route('adminGetReservationsList') }}', {
                        numberOfItemsPerPage: this.itemsPerPage,
                        selectedPage: this.selectedPage
                    })
                        .then(function (response) {
                            app.loader = false;
                            if (response.data.status == 'success') {

                                app.numberOfPages = response.data.pages;

                                if (response.data.reservation_list !== undefined) {
                                    app.reservationList = [];
                                    response.data.reservation_list.forEach(list => {

                                        var date = app.customFormatted(list.start_at);
                                        var time = app.timeFromSqlDatetime(list.start_at);

                                        app.reservationList.push(
                                            {
                                                "id": list.id,
                                                "user_id": list.id, // this is a crutch - never delete it!!!!!
                                                "name": list.name,
                                                "company_name": list.company_name,
                                                "vat_number": list.vat_number,
                                                "paid": list.paid,
                                                "approved": list.approved,
                                                "inBath": list.bath_id,
                                                "parent_id": list.parent_id,
                                                "email": list.email,
                                                "phone": list.phone,
                                                "created_at_date": app.customFormatted(list.created_at),
                                                "created_at_time": app.timeFromSqlDatetime(list.created_at),
                                                "comment": list.comment,
                                                "start_date": date,
                                                "start_time": time,
                                                'number_of_single_baths': list.number_of_single_baths,
                                                'number_of_double_baths': list.number_of_double_baths
                                            }
                                        )
                                    });
                                }
                            }
                        });
                },
                getGroupList: function () {

                    this.loader = true;
                    axios.post('<?php echo e(route('adminGetGroupList')); ?>', {
                        numberOfItemsPerPage: this.itemsPerPage,
                        selectedPage: this.selectedPage
                    })
                        .then(function (response) {
                            app.loader = false;
                            if (response.data.status == 'success') {

                                app.numberOfPages = response.data.pages;

                                if (response.data.status !== undefined) {
                                    app.groupOrderList = [];
                                    response.data[0].forEach(list => {

                                        var date = app.customFormatted(list.start_at);
                                        var time = app.timeFromSqlDatetime(list.start_at);

                                        app.groupOrderList.push(
                                            {
                                                "id": list.id,
                                                "user_id": list.user_id, // this is a crutch - never delete it!!!!!
                                                "bath_needed": list.bath_needed,
                                                "processed": list.processed,
                                                "start_at": list.start_at,
                                                "end_at": list.end_at,
                                                "name": list.name,
                                                "email": list.email,
                                                "phone": list.phone
                                            }
                                        )
                                    });
                                }
                            }
                        });
                },
                setReservation: function () {
                    app.loader = true;
                    axios.post('{{ route('adminSetReservation') }}', {
                        unsortedOrders: app.unsortedOrders,
                        baths: app.baths
                    })
                        .then(function (response) {
                            app.loader = false;
                            app.getReservations();
                        });
                },
                goToThisTime: function (date, time) {
                    var ddd = app.customDateToSql(date);
                    app.selectedDate = new Date(ddd);
                    app.updatedTime(time.split(':')[0]);
                    app.showListOfReservation = false;
                }
            },
            mounted: function () {
                this.selectedHour = this.today.getHours();

                this.getBathsList();
                this.getRoomList();
                //this.getReservations();


            },
            watch: {
                selectedDate: function (val, oldVal) {
                    this.getReservations();
                },
                selectedHour: function (val, oldVal) {
                    this.getReservations();
                }
            }
        });


    </script>
@endsection