@extends('layout.layoutClient')
@section('title')
    Đăng ký
@endsection
@section('body')
<div class="sm:mx-auto ">
    <h2 class="mt-6 text-center text-3xl font-bold text-white ">Đăng ký</h2>
</div>
<div class="max-w-7xl mx-auto" >
    <div class="grid grid-cols-12 space-x-10  " >
        <div class=" col-span-3 flex justify-center items-center">
            <img style=" border-radius: 16px; height: 80vh" src="/img/form/hinh1dk.jpg" alt="Image 1"  />
        </div>
        <div class="min-h-full flex flex-col justify-center sm:px-6 lg:px-8 col-span-6">
            <img src="/img/form/dn.png" 
                alt="Hình ảnh nền" 
                class="object-cover w-full h-100 absolute inset-0"
                style="z-index: -1;" />
                  <!-- Đảm bảo ảnh ở phía dưới -->
           
            <div class=" w-100 relative z-10"> <!-- Đặt z-index cao hơn cho nội dung -->
                <div class=" px-4 sm:rounded-lg">
                    <form class="space-y-3" action="{{ route('register') }}" method="POST">
                        @csrf
                        <div>
                            <label for="name" class="block text-sm font-regular text-white">Họ và tên</label>
                            <div class="mt-1">
                                <input id="name" name="name" type="text" placeholder="Vui lòng nhập họ và tên" 
                                       class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"/>
                                @error('name')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    
                        <div>
                            <label for="email" class="block text-sm font-regular text-white">Email</label>
                            <div class="mt-1">
                                <input id="email" name="email" type="text" placeholder="Vui lòng nhập email" 
                                       class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"/>
                                @error('email')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    
                        <div>
                            <label for="password" class="block text-sm font-regular text-white">Mật khẩu</label>
                            <div class="mt-1">
                                <input id="password" name="password" type="password" placeholder="Vui lòng nhập mật khẩu" 
                                       class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"/>
                                @error('password')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    
                        <div>
                            <label for="confirm_password" class="block text-sm font-regular text-white">Xác nhận mật khẩu</label>
                            <div class="mt-1">
                                <input id="confirm_password" name="password_confirmation" type="password" placeholder="Vui lòng xác nhận mật khẩu" 
                                       class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"/>
                            </div>
                        </div>
                    
                        <div>
                            <label for="phone" class="block text-sm font-regular text-white">Số điện thoại</label>
                            <div class="mt-1">
                                <input id="phone" name="phone" type="number" placeholder="Vui lòng nhập số điện thoại" 
                                       class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"/>
                                @error('phone')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    
                        <div>
                            <label for="date" class="block text-sm font-regular text-white">Ngày sinh</label>
                            <div class="mt-1">
                                <input id="date" name="date" type="date" 
                                       class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"/>
                                @error('date')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    
                        <div class="flex items-center gap-2">
                            <div class="flex items-center">
                                <input id="gender_male" name="gender" type="radio" value="Nam" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"/>
                                <label for="gender_male" class="ml-2 block text-sm text-white">Nam</label>
                            </div>
                            <div class="flex items-center">
                                <input id="gender_female" name="gender" type="radio" value="Nữ" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"/>
                                <label for="gender_female" class="ml-2 block text-sm text-white">Nữ</label>
                            </div>

                            @error('gender')
                             <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    
                        <div>
                            <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Đăng ký
                            </button>
                            
                        </div>
                        <div class="text-white text-center ">

                            <a class="hover:text-red-600 hover:underline" href="{{ route('login') }}">Đã có tài khoản</a>
                        </div>
                    </form>
                    
        
                    <div class="mt-6">
                        <div class="relative">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-300"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-2 bg-white text-gray-500">Hoặc</span>
                            </div>
                        </div>
        
                        <div class="mt-6 grid grid-cols-3 gap-3">
                            <div>
                                <a
                                    href="#"
                                    class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                                >
                                    <span class="sr-only">Sign in with Facebook</span>
                                    <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            fill-rule="evenodd"
                                            d="M20 10c0-5.523-4.477-10-10-10S0 4.477 0 10c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V10h2.54V7.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V10h2.773l-.443 2.89h-2.33v6.988C16.343 19.128 20 14.991 20 10z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </a>
                            </div>
        
                            <div>
                                <a
                                    href="#"
                                    class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                                >
                                    <span class="sr-only">Sign in with Twitter</span>
                                    <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M6.29 18.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0020 3.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.073 4.073 0 01.8 7.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 010 16.407a11.616 11.616 0 006.29 1.84" />
                                    </svg>
                                </a>
                            </div>
        
                            <div>
                                <a
                                    href="#"
                                    class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                                >
                                    <span class="sr-only">Sign in with GitHub</span>
                                    <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 0C4.477 0 0 4.484 0 10.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0110 4.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.203 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.942.359.31.678.921.678 1.856 0 1.338-.013 2.418-.013 2.744 0 .265.18.577.688.479A9.954 9.954 0 0020 10.017C20 4.484 15.523 0 10 0z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-span-3 flex justify-center items-center">
            <img style="border-radius: 16px; height: 80vh" src="/img/form/hinh2dk.jpg" alt="Image 1"  />
        </div>
    </div>
</div>
@endsection
