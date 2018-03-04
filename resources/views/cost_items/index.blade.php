@extends('layouts.master')

@section('content')
<h1 class="center-h1">Статьи затрат</h1>
        <div class="mobile-toogle">
                @if(count($cost_items))

                <table class="table table-bordered">
                  <colgroup></colgroup>
              	       <colgroup class="slim"></colgroup>
                       <thead>
                         <tr class="first-table-tr">
                                 <th><span>Название<span data-text="Название статьи затрат"></span></span></th>
                                 <!-- <td><span>Изменить статью затрат<span data-text="Открыть страницу для изменения статьи затрат"></span></span></td> -->
                         </tr>
                       </thead>


<tbody>


        @foreach($cost_items as $cost_item)

        @include('cost_items.cost_item')

        @endforeach
        </tbody>
                </table>
        </div>
        @endif
        @can('create', App\CostItem::class)

                <a class="btn btn-large btn-success mob-xl open-modal-cost-item-create" href="#">Создать</a>

        @endcan
        <a class="btn btn-large btn-primary mob-xl" href="/set_product_cost_items" style="margin-bottom: 0">Заполнить товары статьями затрат</a></br>



        <div class="modal cost-item-modal ">
            <h2 class="modal-title"></h2>
            <div class="close" onclick="$('.modal').hide()">X</div>
            <div class="modal-content mobile-toogle">

            </div>
        </div>
@endsection
