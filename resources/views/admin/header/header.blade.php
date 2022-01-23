@php
  use App\Models\Notification;
  $desc = Notification::with('desc')->whereIn('notification_type',['PROF_COMP','PROF_DEACT','DOUBT_ANSWER','NEW_DOUBT'])->orWhere('user_id',Auth::user()->user_id)->where('status',false)->get()->toArray();
@endphp
<nav class="navbar navbar-expand-lg navbar-light fixed-top">
    <div class="container-fluid">

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <button type="button" id="sidebarCollapse" class="btn btn-info">
              <i class="fas fa-align-left"></i>
              <span>Toggle Sidebar</span>
          </button>
            <ul class="nav navbar-nav ml-auto">
              <li class="nav-item dropdown notification_dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img src="{{ url('public/images/ic_bell.png') }}">
                  </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <ul class="slim-scroll">
                      @if(isset($desc))
                      @foreach($desc as $data)
                      <li>
                        <p class="noti_pere">
                          @if($data['notification_type']=='PROF_COMP')
                            {{ 'Student Profile Completed' }}
                          @elseif($data['notification_type']=='PROF_DEACT')
                            {{ 'Student Profile Deactivated' }}
                          @elseif($data['notification_type']=='DOUBT_ANSWER')
                            {{ 'New Doubt Answer' }}
                          @elseif($data['notification_type']=='NEW_DOUBT')
                            {{ 'New Doubt Created' }}
                          @elseif($data['notification_type']=='DOUBT_REPLY')
                            {{ $data['desc']['message'] ?? 'Message Not Available'}}
                          @else
                            {{ $data['desc']['message'] ?? 'Message Not Available'}}
                          @endif
                        </p>
                        <div class="close_noti" single-id="{{$data['notification_id']}}">
                          <img src="{{ url('public/images/ic_close_circle.png') }}">
                        </div>
                      </li>
                      @endforeach
                      @endif
                    </ul>
                    <div class="notification_dropdown_footer">
                      <a href="{{route('see.notification')}}" class="theme_btn small_btn">Show All</a>
                    </div>
                  </div>
              </li>
              <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown1" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="profile-cover">
                      <img src="{{ Auth::user()->user_photo ? url('public/storage/'.Config::get('siteglobal.images_dirs.USERS') ).'/'.Auth::user()->user_photo : url('public/images/user.png')}}">
                    </div>
                    <p class="nav-item mr-2 ml-2">{{Auth::user()->name}}</p>
                    <i class="fas fa-chevron-down right-arrow"></i>
                  </a>
                  <div class="dropdown-menu" aria-labelledby="navbarDropdown1">
                     <a class="dropdown-item ajax_request" data-slug="admin/profile" href="{{ route('adminProfile') }}">{{'My Profile'}}</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{route('adminLogout')}}">{{'Logout'}}</a>
                  </div>
              </li>    

            </ul>
            
        </div>
    </div>
</nav>