@extends('layouts.admin')
@section('styles')
@endsection
@section('content')
	@include('partials.subheader')

	<div class="row d-flex justify-content-between">
		<div class="col-md-3 col-xl-4">
		</div>
		<div class="col-md-6 col-xl-4">
			<div class="card shadow">
				<div class="card-img-top {{ $balance < 0 ? 'bg-danger-500' : 'text-success-500' }}">
					<h2 class="text-center display-3 fw-500 p-1">{{$order->customer->name}}</h2>
				</div>
				<div class="card-body">
					<h5 class="fw-500">Order Detail</h5>
					<ul class="list-group">
						<li class="list-group-item d-flex justify-content-between">
							<span>Order Date</span>
							<span>{{ date('d M Y, H:i:s', strtotime($orderDate)) }}</span>
						</li>
						<li class="list-group-item d-flex justify-content-between">
							<span>Order Amount</span>
							<span>{{$order->product->symbol}} {{ number_format($orderAmount, 2, '.', ',') }}</span>
						</li>
						<li class="list-group-item d-flex justify-content-between">
							<span>Full Amount Fees</span>
							<span>{{$order->product->symbol}} {{ number_format($ordercFa, 2, '.', ',') }}</span>
						</li>
						<li class="list-group-item d-flex justify-content-between">
							<span>Deal Rate</span>
							<span>Rp {{ number_format($orderRate, 2, '.', ',') }}</span>
						</li>
						<li class="list-group-item d-flex justify-content-between">
							<span>Charges</span>
							<span>Rp {{ number_format($orderCharges, 2, '.', ',') }}</span>
						</li>
						<li class="list-group-item d-flex justify-content-between" style="background-color: #e9e9e9">
							<span class="text-muted fw-500">Current order</span>
							<span class="fw-700">Rp {{ number_format($orderCurrent, 2, '.', ',') }}</span>
						</li>
						<li class="list-group-item d-flex justify-content-between" style="background-color: #e9e9e9">
							<span class="text-muted fw-500">Your Deposit</span>
							<span class="fw-700">Rp {{ number_format($currentDeposits, 2, '.', ',') }}</span>
						</li>
						<li class="list-group-item">
							<div class="d-flex justify-content-between">
								<span>Balance</span>
								<span class="fw-500 {{ $balance < 0 ? 'text-danger' : 'text-primary' }}">
									Rp {{ number_format($balance, 2, '.', ',') }}
								</span>
							</div>
							<span class="text-muted small">
								@if($balance < 0)
									(Your balance is insufficient for this purchase. Please make payment immediately.)
								@elseif($balance === 0)
									(Your balance is insufficient for the next order.)
								@else
									(Your balance after this purchase.)
								@endif
							</span>
						</li>
						<li class="list-group-item">
							Please Transfer to:<br>
							<div class="row">
								<table class="table table-bordered table-striped table-sm w-100 bg-warning-100">
									<thead class="bg-warning-50">
										<th>Bank</th>
										<th>Account No.</th>
										<th>Acc. Name</th>
									</thead>
									<tbody>
										@foreach ($banks as $bank)
											<tr>
												<td>{{$bank->bank->bank_name}}</td>
												<td>{{$bank->bank->account}}</td>
												<td>{{$bank->bank->acc_name}}</td>
											</tr>
										@endforeach
									</tbody>
								</table>
							</div>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="col-md-3 col-xl-4">
		</div>
	</div>
@endsection


@section('scripts')

@parent

@endsection
