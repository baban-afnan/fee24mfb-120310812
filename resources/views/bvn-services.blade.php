<x-app-layout>
 <x-slot name="title">BVN - Services</x-slot>


<!-- Container-fluid starts-->
          <div class="container-fluid">
            <div class="row icon-main">
              <div class="col-sm-12">
                <div class="card">
                  <div class="card-header card-no-border pb-0">
                    <h3 class="m-b-0 f-w-700">BVN Services</h3>
                  </div>
                  <div class="card-body">
                    <div class="row icon-lists"> 
                      <div class="col-10 col-xxl-2 col-lg-4 col-md-6 icons-item">
                        <a class="sidebar-link sidebar-link-active" href="{{ route('bvnmod.index') }}">
                          <img src="../assets/images/apps/bvnlogo.png" alt="Arrow Up Service" class="mb-2" style="width:40px;height:40px;object-fit:contain;">
                          <h5 class="mt-0">BVN Modification</h5>
                        </a>
                      </div>
                      <div class="col-10 col-xxl-2 col-lg-4 col-md-6 icons-item">
                        <a class="sidebar-link sidebar-link-active" href="{{ route('crmreg.index') }}">
                          <img src="../assets/images/apps/bvnlogo.png" alt="Arrow Up Service" class="mb-2" style="width:40px;height:40px;object-fit:contain;">
                          <h5 class="mt-0">CRM Req</h5>
                        </a>
                      </div>
                      <div class="col-10 col-xxl-2 col-lg-4 col-md-6 icons-item">
                        <a class="sidebar-link sidebar-link-active" href="{{ route('bvnsearch.index') }}">
                          <img src="../assets/images/apps/bvnlogo.png" alt="Arrow Up Service" class="mb-2" style="width:40px;height:40px;object-fit:contain;">
                          <h5 class="mt-0">Search P/N</h5>
                        </a>
                      </div>
                        <div class="col-10 col-xxl-2 col-lg-4 col-md-6 icons-item">
                        <a class="sidebar-link sidebar-link-active" href="{{ route('bvnuser.index') }}">
                          <img src="../assets/images/apps/bvnlogo.png" alt="Arrow Up Service" class="mb-2" style="width:40px;height:40px;object-fit:contain;">
                          <h5 class="mt-0">BVN User Req</h5>
                        </a>
                      </div>
                        <div class="col-10 col-xxl-2 col-lg-4 col-md-6 icons-item">
                         <a class="sidebar-link sidebar-link-active" href="{{ route('sendvnin.index') }}">
                          <img src="../assets/images/apps/bvnlogo.png" alt="Arrow Up Service" class="mb-2" style="width:40px;height:40px;object-fit:contain;">
                          <h5 class="mt-0">Send Vnin/NIBSS</h5>
                        </a>
                      </div>
                </div>
               </div>
            </div>


</x-app-layout>