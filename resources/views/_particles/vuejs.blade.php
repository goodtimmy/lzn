<script src="{{ URL::asset('assets/js/axios.min.js') }}"></script>
<?php if( env('APP_DEBUG') === false ): ?>
<script src="{{ URL::asset('assets/js/vue.js') }}"></script>
<?php else: ?>
<script src="{{ URL::asset('assets/js/vue-debug.js') }}"></script>
<?php endif; ?>

<script src="{{ URL::asset('assets/js/moment.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/vuejs-datepicker.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/datepicker-' . Lang::locale() . '.js') }}"></script>
