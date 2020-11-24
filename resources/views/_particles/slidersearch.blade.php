<!-- begin:header -->
    <div id="header" class="header-slide">
      <div class="container">
        <div class="row">
          <div class="col-md-5">
            <div class="quick-search">
              <div class="row">
                 
                 {!! Form::open(array('url' => array('searchproperties'),'name'=>'search_form','id'=>'search_form','role'=>'form')) !!}   
                   
                  <div class="col-md-6 col-sm-6 col-xs-6">
                    <div class="form-group">
                      <label for="city">Район</label>
                      <select class="form-control" name="city_id">
                        @foreach($city_list as $city)  
						
							<option value="{{$city->id}}">{{$city->city_name}}</option>
										
						@endforeach
                         
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="purpose">Вид недвижимости</label>
                      <select class="form-control" name="purpose">
                        <option value="Sale">Продажа</option>
                        <option value="Rent">Аренда</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="minprice">Цена от</label>
                      <input type="text" name="min_price" class="form-control" placeholder="В чешских кронах"> 
                    </div>
                  </div>
                  <!-- break -->
                   <div class="col-md-6 col-sm-6 col-xs-6">
                    <div class="form-group">
                      <label for="type">Тип</label>
                      <select class="form-control" name="type">
                        @foreach(\App\Types::orderBy('types')->get() as $type)
                        <option value="{{$type->id}}">{{$type->types}}</option>
						@endforeach

                      </select>
                    </div>
                    <div class="form-group">
                      <label for="maxprice">Цена до</label>
                      <input type="text" name="max_price" class="form-control" placeholder="В чешских кронах"> 
                    </div>
                  </div>
                  <div class="col-md-12 col-sm-12 col-xs-12"><input type="submit" name="submit" value="Искать" class="btn btn-primary btn-lg btn-block"></div>

                {!! Form::close() !!} 
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- end:header -->