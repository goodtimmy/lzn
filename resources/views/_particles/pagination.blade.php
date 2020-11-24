@if ($paginator->lastPage() > 1)
<div class="row">
      <div class="col-md-12">
			<ul class="pagination">
			    <li class="{{ ($paginator->currentPage() == 1) ? ' disabled' : '' }}">
			        <a href="{{ $paginator->url(1) }}">Назад</a>
			    </li>
			    @for ($i = 1; $i <= $paginator->lastPage(); $i++)
			        <li class="{{ ($paginator->currentPage() == $i) ? ' active' : '' }}">
			            <a href="{{ $paginator->url($i) }}">{{ $i }}</a>
			        </li>
			    @endfor
			    <li class="{{ ($paginator->currentPage() == $paginator->lastPage()) ? ' disabled' : '' }}">
			        <a href="{{ $paginator->url($paginator->currentPage()+1) }}" >Далее</a>
			    </li>
			</ul>
	</div>
</div>	
@endif

 