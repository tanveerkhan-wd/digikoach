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

        <li class="{{ (request()->is('admin/appUsers')) || (request()->is('admin/appUsers/*')) || (request()->is('admin/editAppUser/*')) || (request()->is('admin/viewAppUser/*')) ? 'active' : '' }}">
            <a class="ajax_request" data-slug="admin/appUsers" href="{{url('/admin/appUsers')}}">
               <img src="{{ url('public/images/ic_users.png') }}" class="color">
               <img src="{{ url('public/images/ic_users_color.png') }}" class="selected">
                {{'App Users'}}
            </a>
        </li>

        <li class="{{ (request()->is('admin/accessPrivileges/*')) || (request()->is('admin/subAdmin')) || (request()->is('admin/subAdmin/*')) || (request()->is('admin/editSubAdmin/*')) || (request()->is('admin/viewSubAdmin/*')) ? 'active' : '' }}">
            <a  class="ajax_request" data-slug="admin/subAdmin" href="{{url('/admin/subAdmin')}}">
               <img src="{{ url('public/images/ic_profile.png') }}" class="color">
               <img src="{{ url('public/images/ic_profile_color.png') }}" class="selected">
                {{'Sub-admins'}}
            </a>
        </li>

        <li class="{{ (request()->is('admin/category')) || (request()->is('admin/editCategory')) || (request()->is('admin/addCategory')) ? 'active' : '' }}">
            <a class="ajax_request" data-slug="admin/category" href="{{url('/admin/category')}}">
               <img src="{{ url('public/images/ic_menu.png') }}" class="color">
               <img src="{{ url('public/images/ic_menu_color.png') }}" class="selected">
                {{'Category'}}
            </a>
        </li>

        <li class="{{ (request()->is('admin/questions*')) || (request()->is('admin/questions')) || (request()->is('admin/addQuestion')) || (request()->is('admin/viewQuestion/*'))  || (request()->is('admin/editQuestion/*'))  ? 'active' : '' }}">
            <a data-slug="admin/questions" href="{{url('/admin/questions')}}">
               <img src="{{ url('public/images/ic_lock.png') }}" class="color">
               <img src="{{ url('public/images/ic_lock_color.png') }}" class="selected">
                {{'Question Bank'}}
            </a>
        </li>

        <li class="{{ (request()->is('admin/liveTest/appearedStudents/*')) || (request()->is('admin/liveTest*')) || (request()->is('admin/liveTest')) || (request()->is('admin/addLiveTest')) || (request()->is('admin/viewLiveTest/*'))  || (request()->is('admin/editLiveTest/*')) || (request()->is('admin/appearedStudents/*')) ? 'active' : '' }}">
            <a href="{{url('/admin/liveTest')}}">
               <img src="{{ url('public/images/ic_test-results.png') }}" class="color">
               <img src="{{ url('public/images/ic_test-results_color.png') }}" class="selected">
                {{'Live Test'}}
            </a>
        </li>

        <li class="{{ (request()->is('admin/quizTest/appearedStudents/*')) || (request()->is('admin/quizTest*')) || (request()->is('admin/quizTest')) || (request()->is('admin/addQuizTest')) || (request()->is('admin/viewQuizTest/*'))  || (request()->is('admin/editQuizTest/*')) ? 'active' : '' }}">
            <a class="ajax_request" data-slug="admin/quizTest" href="{{url('/admin/quizTest')}}">
               <img src="{{ url('public/images/ic_ebook.png') }}" class="color">
               <img src="{{ url('public/images/ic_ebook_color.png') }}" class="selected">
                {{'Quizzes'}}
            </a>
        </li>

        <li class="{{ (request()->is('admin/practiceTest/appearedStudents/*')) || (request()->is('admin/practiceTest*')) || (request()->is('admin/practiceTest')) || (request()->is('admin/addPracticeTest')) || (request()->is('admin/viewPracticeTest/*'))  || (request()->is('admin/editPracticeTest/*')) ? 'active' : '' }}">
            <a  class="ajax_request" data-slug="admin/practiceTest" href="{{url('/admin/practiceTest')}}">
               <img src="{{ url('public/images/ic_test-results.png') }}" class="color">
               <img src="{{ url('public/images/ic_test-results_color.png') }}" class="selected">
                {{'Practice Test'}}
            </a>
        </li>

        <li class="{{ (request()->is('admin/gkCa/quizTest*')) || (request()->is('admin/gkca/quizTest/appearedStudents/*')) || (request()->is('admin/gkCa/addQuizTest')) || (request()->is('admin/gkCa/viewQuizTest/*')) || (request()->is('admin/gkCa/editQuizTest/*')) || (request()->is('admin/gkCa/quizTest')) || (request()->is('admin/gkCa/questionBank')) || (request()->is('admin/gkCa/editArticleNews/*')) || (request()->is('admin/gkCa/viewArticleNews/*')) || (request()->is('admin/gkCa/addArticleNews')) || (request()->is('admin/gkCa/articleNews'))  ? 'active' : '' }}">
          <a href="#pageSubmenu1" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
               <img src="{{ url('public/images/ic_educational-programs.png') }}" class="color">
               <img src="{{ url('public/images/ic_educational-programs_color.png') }}" class="selected">
                {{'GK & CA'}}
                <i class="cfa fas fa-chevron-down right-arrow"></i>
          </a>
          <ul class="collapse list-unstyled {{ (request()->is('admin/gkCa/quizTest*')) || (request()->is('admin/gkca/quizTest/appearedStudents/*')) ||(request()->is('admin/gkCa/addQuizTest')) || (request()->is('admin/gkCa/viewQuizTest/*')) || (request()->is('admin/gkCa/editQuizTest/*')) || (request()->is('admin/gkCa/quizTest')) || (request()->is('admin/gkCa/questionBank')) ||  (request()->is('admin/gkCa/editArticleNews/*')) || (request()->is('admin/gkCa/viewArticleNews/*')) || (request()->is('admin/gkCa/addArticleNews')) || (request()->is('admin/gkCa/articleNews'))  ? 'show' : '' }}" id="pageSubmenu1">
                <li class="{{ (request()->is('admin/gkCa/editArticleNews/*')) || (request()->is('admin/gkCa/viewArticleNews/*')) || (request()->is('admin/gkCa/addArticleNews')) || (request()->is('admin/gkCa/articleNews'))  ? 'active' : '' }}">
                    <a class="ajax_request" data-slug="admin/gkCa/articleNews" href="{{url('admin/gkCa/articleNews')}}">Articles & News</a>
                </li>
                
                <li class="{{ (request()->is('admin/gkCa/quizTest*')) || (request()->is('admin/gkCa/quizTest/appearedStudents/*')) || (request()->is('admin/gkCa/quizTest')) || (request()->is('admin/gkCa/addQuizTest')) || (request()->is('admin/gkCa/viewQuizTest/*')) || (request()->is('admin/gkCa/editQuizTest/*')) ? 'active' :'' }}">
                    <a class="ajax_request" data-slug="admin/gkCa/quizTest" href="{{url('admin/gkCa/quizTest')}}">Quizzes</a>
                </li>
          </ul>                   
        </li>

        <li class="{{ (request()->is('admin/editBlog/*')) || (request()->is('admin/viewBlog/*')) || (request()->is('admin/addBlog')) || (request()->is('admin/blog'))  ? 'active' : '' }}">
          <a href="#pageSubmenu2" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
             <img src="{{ url('public/images/ic_reports.png') }}" class="color">
             <img src="{{ url('public/images/ic_reports_color.png') }}" class="selected">
              {{'Blog'}}
              <i class="cfa fas fa-chevron-down right-arrow"></i>
          </a>
          <ul class="collapse list-unstyled {{ (request()->is('admin/editBlog/*')) || (request()->is('admin/viewBlog/*')) || (request()->is('admin/addBlog')) || (request()->is('admin/blog')) ? 'show' : '' }}" id="pageSubmenu2">
              <li class="{{ (request()->is('admin/blogCategory')) ? 'active' : '' }}">
                  <a class="ajax_request" data-slug="admin/blogCategory" href="{{url('admin/blogCategory')}}">Blog Categories</a>
              </li>
              <li class="{{ (request()->is('admin/editBlog/*')) || (request()->is('admin/viewBlog/*')) || (request()->is('admin/addBlog')) || (request()->is('admin/blog')) ? 'active' : '' }}">
                  <a class="ajax_request" data-slug="admin/blog" href="{{url('admin/blog')}}">Blogs</a>
              </li>
          </ul>                   
        </li>

        <li class="{{ request()->is('admin/doubt') || request()->is('admin/viewDoubt/*') }}">
            <a class="ajax_request" data-slug="admin/doubt" href="{{url('admin/doubt')}}">
               <img src="{{ url('public/images/ic_modules.png') }}" class="color">
               <img src="{{ url('public/images/ic_modules_color.png') }}" class="selected">
                {{'Doubt Section'}}
            </a>
        </li>

        <li class="{{ (request()->is('admin/imageMedia')) || (request()->is('admin/addImageMedia')) || (request()->is('admin/translation')) || (request()->is('admin/setting')) || (request()->is('admin/bannerImage')) || (request()->is('admin/feature')) || (request()->is('admin/cms')) || (request()->is('admin/editCms/*')) || (request()->is('admin/testimonial')) ||  (request()->is('admin/emailTemplates')) || (request()->is('admin/emailTemplate/edit/*')) ? 'active' : '' }}">
          <a href="#pageSubmenu3" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
               <img src="{{ url('public/images/ic_monitor.png') }}" class="color">
               <img src="{{ url('public/images/ic_monitor_color.png') }}" class="selected">
                {{'Master'}}
                <i class="cfa fas fa-chevron-down right-arrow"></i>
          </a>
          <ul class="collapse {{  (request()->is('admin/imageMedia')) || (request()->is('admin/addImageMedia')) || (request()->is('admin/translation')) || (request()->is('admin/setting')) || (request()->is('admin/bannerImage')) || (request()->is('admin/feature')) || (request()->is('admin/cms')) || (request()->is('admin/editCms/*')) || (request()->is('admin/testimonial')) || (request()->is('admin/emailTemplates')) || (request()->is('admin/emailTemplate/edit/*')) ? 'show' : '' }}" id="pageSubmenu3">
                
                <li class="{{  (request()->is('admin/translation')) ? 'active' : '' }}">
                    <a class="ajax_request" data-slug="admin/translation" href="{{url('admin/translation')}}">
                        {{'Translations'}}
                    </a>
                </li>

                <li class="{{  (request()->is('admin/setting')) ? 'active' : '' }}">
                    <a class="ajax_request" data-slug="admin/setting" href="{{url('admin/setting')}}">
                        {{'Home Page Settings'}}
                    </a>
                </li>

                <li class="{{  (request()->is('admin/bannerImage')) ? 'active' : '' }}">
                    <a class="ajax_request" data-slug="admin/bannerImage" href="{{url('admin/bannerImage')}}">
                        {{'Banner Images'}}
                    </a>
                </li>
                <li class="{{ (request()->is('admin/testRule')) ? 'active' : '' }}">
                    <a class="ajax_request" data-slug="admin/testRule" href="{{url('admin/testRule')}}">
                        {{'Test Rule'}}
                    </a>
                </li>
                <li class="{{  (request()->is('admin/feature')) ? 'active' : '' }}">
                    <a class="ajax_request" data-slug="admin/feature" href="{{url('admin/feature')}}">
                        {{'Feature'}}
                    </a>
                </li>

                <li class="{{ (request()->is('admin/testimonial')) ? 'active' : '' }}">
                    <a class="ajax_request" data-slug="admin/testimonial" href="{{url('admin/testimonial')}}">
                        {{'Testimonial'}}
                    </a>
                </li>

                <li class="{{ (request()->is('admin/editCms/*')) || (request()->is('admin/cms')) ? 'active' : '' }}">
                    <a class="ajax_request" data-slug="admin/cms" href="{{url('admin/cms')}}">
                        {{'CMS'}}
                    </a>
                </li>

                <li class="{{ (request()->is('admin/emailTemplates')) || (request()->is('admin/emailTemplate/edit/*')) ? 'active' : '' }}">
                    <a class="ajax_request" data-slug="admin/emailTemplates" href="{{url('admin/emailTemplates')}}">
                        {{'Email Templates'}}
                    </a>
                </li>

                <li class="{{ (request()->is('admin/imageMedia')) || (request()->is('admin/addImageMedia')) ? 'active' : '' }}">
                    <a class="ajax_request" data-slug="admin/imageMedia" href="{{url('admin/imageMedia')}}">
                        {{'Image Media'}}
                    </a>
                </li>
          </ul>                   
        </li>

    <div class="bottom-link">
      {{-- <a href="#" class="help-link"><img src="{{ url('public/images/ic_comment.png') }}" >{{$translations['gn_help'] ?? 'Help'}}?</a>
      <a href="{{route('adminLogout')}}" class="logout"><img src="{{ url('public/images/ic_logout.png') }}" ></a> --}}
    </div>
    </ul>

   
</nav>