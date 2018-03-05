@extends('layouts.master')

@section('content')
<div ng-controller="cost-item" class="main-body ng-scope flex" data-ui-view="" data-flex="">
    <md-card class="md-table ng-scope _md">
        <md-card-content>
          <div class="md-table-loader" data-ng-if="!loaded">
            <md-progress-circular md-mode="indeterminate"></md-progress-circular>
        </div>
        <section class="md-table-header">
            <div class="md-table-header-title">
                <span ng-click="toggleRight()" >Статьи затрат</span>
            </div>
        </section>
        <section class="md-table-body">
<!-- <h1 class="center-h1">Статьи затрат</h1> -->
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
          <md-button class="md-primary md-raised" ng-click="CostItemsCreatess($event)">
          Создать
          </md-button>

        @endcan
        <a class="btn btn-large btn-primary mob-xl" href="/set_product_cost_items" style="margin-bottom: 0">Заполнить товары статьями затрат</a></br>



        <div class="modal cost-item-modal ">
            <h2 class="modal-title"></h2>
            <div class="close" onclick="$('.modal').hide()">X</div>
            <div class="modal-content mobile-toogle">

            </div>
        </div>
</section>
</md-card-content>
</md-card>
</div>
@endsection
