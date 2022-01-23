<nav id="sidebar" class="">
    <div class="sidebar-header">
        <a href="{{route('adminDashboard')}}">
          <img src="{{url('public/images/digikoach_Admin_logo.png')}}" style="height: 45px;width: auto;">
        </a>
    </div>

    <ul class="list-unstyled components">
        <li class="{{ (request()->is('admin/dashboard')) ? 'active' : '' }}">
            <a href="{{route('adminDashboard')}}">
               <img src="{{ url('public/images/ic_dashoard.png') }}" class="color">
               <img src="{{ url('public/images/ic_dashoard_color.png') }}" class="selected">
                {{'Dashboard'}}
            </a>
        </li>
        @php
          $accessPriData = session()->get('accessPriData');
        @endphp
        @if(!empty($accessPriData))
        @if($accessPriData['Question_Bank_Live_Test']->view==true || $accessPriData['Question_Bank_Quizz_Test']->view==true || $accessPriData['Question_Bank_Practice_Test']->view==true || $accessPriData['Question_Bank_GK_CA_Test']->view==true )

        <li class="{{ (request()->is('admin/questions')) || (request()->is('admin/addQuestion')) || (request()->is('admin/viewQuestion/*'))  || (request()->is('admin/editQuestion/*'))  ? 'active' : '' }}">
            <a data-slug="admin/questions" href="{{url('/admin/questions')}}">
               <img src="{{ url('public/images/ic_lock.png') }}" class="color">
               <img src="{{ url('public/images/ic_lock_color.png') }}" class="selected">
                {{'Question Bank'}}
            </a>
        </li>

        @endif

        @if(!empty($accessPriData['Live_Test']) && $accessPriData['Live_Test']->view==true )
        <li class="{{ (request()->is('admin/liveTest/appearedStudents/*')) || (request()->is('admin/liveTest')) || (request()->is('admin/addLiveTest')) || (request()->is('admin/viewLiveTest/*'))  || (request()->is('admin/editLiveTest/*')) || (request()->is('admin/appearedStudents/*')) ? 'active' : '' }}">
            <a {{-- class="ajax_request" data-slug="admin/liveTest" --}} href="{{url('/admin/liveTest')}}">
               <img src="{{ url('public/images/ic_test-results.png') }}" class="color">
               <img src="{{ url('public/images/ic_test-results_color.png') }}" class="selected">
                {{'Live Test'}}
            </a>
        </li>
        @endif

        @if(!empty($accessPriData['Quizz_Test']) && $accessPriData['Quizz_Test']->view==true )
        <li class="{{ (request()->is('admin/quizTest/appearedStudents/*')) || (request()->is('admin/quizTest')) || (request()->is('admin/addQuizTest')) || (request()->is('admin/viewQuizTest/*'))  || (request()->is('admin/editQuizTest/*')) ? 'active' : '' }}">
            <a class="ajax_request" data-slug="admin/quizTest" href="{{url('/admin/quizTest')}}">
               <img src="{{ url('public/images/ic_ebook.png') }}" class="color">
               <img src="{{ url('public/images/ic_ebook_color.png') }}" class="selected">
                {{'Quizzes'}}
            </a>
        </li>
        @endif

        @if(!empty($accessPriData['Practice_Test']) && $accessPriData['Practice_Test']->view==true )
        <li class="{{ (request()->is('admin/practiceTest/appearedStudents/*')) || (request()->is('admin/practiceTest')) || (request()->is('admin/addPracticeTest')) || (request()->is('admin/viewPracticeTest/*'))  || (request()->is('admin/editPracticeTest/*')) ? 'active' : '' }}">
            <a  class="ajax_request" data-slug="admin/practiceTest" href="{{url('/admin/practiceTest')}}">
               <img src="{{ url('public/images/ic_test-results.png') }}" class="color">
               <img src="{{ url('public/images/ic_test-results_color.png') }}" class="selected">
                {{'Practice Test'}}
            </a>
        </li>
        @endif

        @if(!empty($accessPriData['GK_CA_Quizz']) && $accessPriData['GK_CA_Quizz']->view==true || !empty($accessPriData['Article_News']) && $accessPriData['Article_News']->view==true )
        <li class="{{ (request()->is('admin/gkca/quizTest/appearedStudents/*')) || (request()->is('admin/gkCa/addQuizTest')) || (request()->is('admin/gkCa/viewQuizTest/*')) || (request()->is('admin/gkCa/editQuizTest/*')) || (request()->is('admin/gkCa/quizTest')) || (request()->is('admin/gkCa/questionBank')) || (request()->is('admin/gkCa/editArticleNews/*')) || (request()->is('admin/gkCa/viewArticleNews/*')) || (request()->is('admin/gkCa/addArticleNews')) || (request()->is('admin/gkCa/articleNews'))  ? 'active' : '' }}">
          <a href="#pageSubmenu1" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
               <img src="{{ url('public/images/ic_educational-programs.png') }}" class="color">
               <img src="{{ url('public/images/ic_educational-programs_color.png') }}" class="selected">
                {{'GK & CA'}}
                <i class="cfa fas fa-chevron-down right-arrow"></i>
          </a>
          <ul class="collapse list-unstyled {{ (request()->is('admin/gkca/quizTest/appearedStudents/*')) ||(request()->is('admin/gkCa/addQuizTest')) || (request()->is('admin/gkCa/viewQuizTest/*')) || (request()->is('admin/gkCa/editQuizTest/*')) || (request()->is('admin/gkCa/quizTest')) || (request()->is('admin/gkCa/questionBank')) ||  (request()->is('admin/gkCa/editArticleNews/*')) || (request()->is('admin/gkCa/viewArticleNews/*')) || (request()->is('admin/gkCa/addArticleNews')) || (request()->is('admin/gkCa/articleNews'))  ? 'show' : '' }}" id="pageSubmenu1">
                @if(!empty($accessPriData['Article_News']) && $accessPriData['Article_News']->view==true )
                <li class="{{ (request()->is('admin/gkCa/editArticleNews/*')) || (request()->is('admin/gkCa/viewArticleNews/*')) || (request()->is('admin/gkCa/addArticleNews')) || (request()->is('admin/gkCa/articleNews'))  ? 'active' : '' }}">
                    <a class="ajax_request" data-slug="admin/gkCa/articleNews" href="{{url('admin/gkCa/articleNews')}}">Articles & Newes</a>
                </li>
                @endif
                @if(!empty($accessPriData['GK_CA_Quizz']) && $accessPriData['GK_CA_Quizz']->view==true )
                <li class="{{ (request()->is('admin/gkCa/quizTest/appearedStudents/*')) || (request()->is('admin/gkCa/quizTest')) || (request()->is('admin/gkCa/addQuizTest')) || (request()->is('admin/gkCa/viewQuizTest/*')) || (request()->is('admin/gkCa/editQuizTest/*')) ? 'active' :'' }}">
                    <a class="ajax_request" data-slug="admin/gkCa/quizTest" href="{{url('admin/gkCa/quizTest')}}">Quizzes</a>
                </li>
                @endif
          </ul>                   
        </li>
        @endif
        
        @if(!empty($accessPriData['Blog_Categories']) && $accessPriData['Blog_Categories']->view==true || !empty($accessPriData['Blog_Post']) && $accessPriData['Blog_Post']->view==true )
        <li class="{{ (request()->is('admin/editBlog/*')) || (request()->is('admin/viewBlog/*')) || (request()->is('admin/addBlog')) || (request()->is('admin/blog'))  ? 'active' : '' }}">
          <a href="#pageSubmenu2" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
             <img src="{{ url('public/images/ic_reports.png') }}" class="color">
             <img src="{{ url('public/images/ic_reports_color.png') }}" class="selected">
              {{'Blog'}}
              <i class="cfa fas fa-chevron-down right-arrow"></i>
          </a>
          <ul class="collapse list-unstyled {{ (request()->is('admin/editBlog/*')) || (request()->is('admin/viewBlog/*')) || (request()->is('admin/addBlog')) || (request()->is('admin/blog')) ? 'show' : '' }}" id="pageSubmenu2">
              @if(!empty($accessPriData['Blog_Categories']) && $accessPriData['Blog_Categories']->view==true )
              <li class="{{ (request()->is('admin/blogCategory')) ? 'active' : '' }}">
                  <a class="ajax_request" data-slug="admin/blogCategory" href="{{url('admin/blogCategory')}}">Blog Categories</a>
              </li>
              @endif
              @if(!empty($accessPriData['Blog_Post']) && $accessPriData['Blog_Post']->view==true )
              <li class="{{ (request()->is('admin/editBlog/*')) || (request()->is('admin/viewBlog/*')) || (request()->is('admin/addBlog')) || (request()->is('admin/blog')) ? 'active' : '' }}">
                  <a class="ajax_request" data-slug="admin/blog" href="{{url('admin/blog')}}">Blogs</a>
              </li>
              @endif
          </ul>                   
        </li>
        @endif

        @if(!empty($accessPriData['Doubts']) && $accessPriData['Doubts']->view==true )
        
        <li class="{{ request()->is('admin/doubt') || request()->is('admin/viewDoubt/*') }}">
            <a class="ajax_request" data-slug="admin/doubt" href="{{url('admin/doubt')}}">
               <img src="{{ url('public/images/ic_modules.png') }}" class="color">
               <img src="{{ url('public/images/ic_modules_color.png') }}" class="selected">
                {{'Doubt Section'}}
            </a>
        </li>

        @endif
      @endif      
      <div class="bottom-link">
        {{-- <a href="#" class="help-link"><img src="{{ url('public/images/ic_comment.png') }}" >{{$translations['gn_help'] ?? 'Help'}}?</a>
        <a href="{{route('adminLogout')}}" class="logout"><img src="{{ url('public/images/ic_logout.png') }}" ></a> --}}
      </div>
    </ul>

   
</nav>