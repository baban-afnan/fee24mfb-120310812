<x-app-layout>
    <x-slot name="title">Wallet Management Control Form</x-slot>

    <!-- Container-fluid starts -->
    <div class="container-fluid">
        <div class="row icon-main">
            <div class="col-sm-12">
                <div class="card">
                    
                    <!-- Card Header -->
                    <div class="card-header card-no-border pb-0">
                        <h3 class="m-b-0 f-w-700">System Management</h3>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="row icon-lists">
                            
                            <!-- Services -->
                            <div class="col-10 col-xxl-2 col-lg-4 col-md-6 icons-item">
                                <a href="#">
                                    <img src="{{ asset('assets/images/apps/modify.png') }}" 
                                         alt="Services" 
                                         class="mb-2" 
                                         style="width:40px;height:40px;object-fit:contain;">
                                    <h5 class="mt-0">Services</h5>
                                </a>
                            </div>

                            <!-- Price Management -->
                            <div class="col-10 col-xxl-2 col-lg-4 col-md-6 icons-item">
                                <a href="#">
                                    <img src="{{ asset('assets/images/apps/fund.png') }}" 
                                         alt="Price Management" 
                                         class="mb-2" 
                                         style="width:40px;height:40px;object-fit:contain;">
                                    <h5 class="mt-0">Price Management</h5>
                                </a>
                            </div>

                            <!-- Users -->
                            <div class="col-10 col-xxl-2 col-lg-4 col-md-6 icons-item">
                                <a class="sidebar-link sidebar-link-active" href="{{ route('users.index') }}">
                                    <img src="{{ asset('assets/images/apps/agent.jpg') }}" 
                                         alt="Users" 
                                         class="mb-2" 
                                         style="width:40px;height:40px;object-fit:contain;">
                                    <h5 class="mt-0">Users</h5>
                                </a>
                            </div>

                            <!-- Notifications -->
                            <div class="col-10 col-xxl-2 col-lg-4 col-md-6 icons-item">
                                <a href="{{route('notification.index') }}">
                                    <img src="{{ asset('assets/images/apps/email.png') }}" 
                                         alt="Notifications" 
                                         class="mb-2" 
                                         style="width:40px;height:40px;object-fit:contain;">
                                    <h5 class="mt-0">Notifications</h5>
                                </a>
                            </div>

                            <!-- Email -->
                            <div class="col-10 col-xxl-2 col-lg-4 col-md-6 icons-item">
                                <a href="{{route('admin.email.create')}}">
                                    <img src="{{ asset('assets/images/apps/email.png') }}" 
                                         alt="Email" 
                                         class="mb-2" 
                                         style="width:40px;height:40px;object-fit:contain;">
                                    <h5 class="mt-0">Email</h5>
                                </a>
                            </div>

                        </div> <!-- End row -->
                    </div> <!-- End card body -->

                </div> <!-- End card -->
            </div>
        </div>
    </div>
</x-app-layout>
