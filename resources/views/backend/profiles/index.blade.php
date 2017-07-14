@extends('layouts.app')

@section('content')
<div class="page-content">
  <div class="content">
    <div class="row">

      <!-- LEFT -->
      <div class="col-md-6">
        <div class="tiles white col-md-12 no-padding">
          <div class="tiles green cover-pic-wrapper">
            <img src="{{ asset('assets/img/cover.jpg') }}" alt="">
          </div>

          <div class="tiles white">
            <div class="row">
              <div class="col-md-3 col-sm-3" >
                <div class="user-profile-pic">
                  @if (Auth::user()->photo)
                    <img width="69" height="69" src="{{ asset('img/profile/' . Auth::user()->photo) }}" alt="">
                  @else
                    <img width="69" height="69" src="{{ asset('img/profile/default.png') }}" alt="">
                  @endif
                </div>
                 <div class="user-mini-description">
                  <h3 class="text-success semi-bold">
                  {{ Auth::user()->interactions()->count() }}
                  </h3>
                  <h5>Chamados</h5>
                  <h3 class="text-success semi-bold">
                  {{ Auth::user()->logs()->count() }}
                  </h3>
                  <h5>Ações</h5>
                </div>
              </div>

              <div class="col-md-5 user-description-box col-sm-5">
                <h4 class="semi-bold m-t-0 m-b-5">{{ Auth::user()->name }}</h4>

                <h6 class="profile-portal-name">Unidade {{ $currentPortal->territory }}</h6>
                <h6 class="m-t-5 m-b-15">{{ Auth::user()->getRoleName() }}</h6>

                <p><i class="fa fa-briefcase"></i>{{ Auth::user()->getResponsabilityName() ? Auth::user()->getResponsabilityName() : Auth::user()->getDepartmentName() }}</p>
                <p><i class="fa fa-globe"></i>{{ str_replace('http://','',$currentPortal->url) }}</p>
                <p><i class="fa fa-envelope"></i>{{ Auth::user()->email }}</p>
                <p><i class="fa fa-phone"></i>{{ Auth::user()->phone? Auth::user()->phone : Auth::user()->cell_phone }}</p>
              </div>

              <div class="col-md-3 col-sm-3">
                <h5 class="normal">Parceiros ( <span class="text-success">{{ count($friends) }}</span> )</h5>
                <ul class="my-friends">
                  @foreach ($friends as $friend)
                  <li>
                    <div class="profile-pic">
                      @if ($friend->photo)
                        <img width="35" height="35" src="{{ asset('img/profile/' . $friend->photo) }}" alt="" data-toggle="tooltip" title="{{ $friend->name }}">
                      @else
                        <img width="35" height="35" src="{{ asset('img/profile/default.png') }}" alt="" data-toggle="tooltip" title="{{ $friend->name }}">
                      @endif
                    </div>
                  </li>
                  @endforeach
                </ul>
                <div class="clearfix"></div>
              </div>
            </div>

            <div class="tiles-body">

              @include('backend.profiles.partials.logs')

            </div>
          </div>
        </div>
      </div>


      <!-- RIGHT -->
      <div class="col-md-6">
        @include('backend.profiles.partials.tickets')
      </div>
    </div>

  </div>
</div>
@endsection
