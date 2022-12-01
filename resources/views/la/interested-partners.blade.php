@extends("la.layouts.app")

@section("contentheader_title", "Interested Partners")
@section("contentheader_description", "Interested Partners Listing")
@section("section", "Interested Partners")
@section("sub_section", "Listing")
@section("htmlheader_title", "Interested Partners listing")

@section("main-content")

    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session()->has('success'))
        <p class="alert alert-success">{{ session()->get('success') }}</p>
    @endif
    <div class="box box-success">
        <div class="box-body">
            <table id="table" class="table table-bordered">
                <thead>
                <tr>
                    <th>Feature Name</th>
                    <th>Partner Name</th>
                    <th>Partner Email</th>
                    <th>Partner Phone</th>
                    <th>Requested Date</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($interestedPartners as $key => $interestedPartner)
                        @php
                            $requestedDate = $interestedPartner->created_at;
                        @endphp
                        <tr>
                            <td>
                                {{$interestedPartner->feature_name}}
                            </td>
                            <td>
                                <a href="{{ url(config('laraadmin.adminRoute') . '/users/') .'/'. $interestedPartner->user_id }}">{{$interestedPartner->user->name}}</a>
                            </td>
                            <td>
                                {{$interestedPartner->user->email}}
                            </td>
                            <td>
                                {{$interestedPartner->user->mobile}}
                            </td>
                            <td>
                                {{$requestedDate->toDateString()}}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('la-assets/plugins/datatables/datatables.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('la-assets/css/daterangepicker.css') }}"/>
@endpush

@push('scripts')
    <script src="{{ asset('la-assets/plugins/datatables/datatables.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('la-assets/js/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('la-assets/js/daterangepicker.min.js') }}"></script>
    <script>
        $(function () {
            $('#table').DataTable({
                "ordering": false,
            });
        });
    </script>
@endpush