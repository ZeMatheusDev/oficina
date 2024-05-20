<template >

  <TopBar>
    <div class="bg-white w-full fixed px-2 shadow-sm top-0 z-20">
      <div class="lg:ml-60 p-3 flex items-center transition-all duration-300" :class="{'lg:ml-20': toggleMenu}">
        <button @click="toggleMenu = !toggleMenu" class="max-lg:hidden">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7" />
          </svg>
        </button>
        <button @click="toggleMenuMobile = !toggleMenuMobile" class="lg:hidden">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7" />
          </svg>
        </button>
       
        <div class="flex ml-auto items-center">
          <div class="flex flex-col mr-3">
            
            <span class="text-[15px] font-semibold">{{$page.props?.user?.name}}</span>
            <span class="text-[12px] text-gray-500">{{ $page.props?.user?.email }}</span>
          </div>
          <img class="rounded-full h-10 w-10 object-cover" alt="" :src="$page.props.user?.profile_picture" onerror="this.src='/images/profile_default.png';">
        </div>      

        <Link class="flex text-gray-500/80 font-semibold space-x-3 items-center text-[14px]" href="/logout" method="post">
        <svg version="1.1" fill="currentColor" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 330 330" style="enable-background:new 0 0 330 330;" class="h-5 w-5 ml-5 text-red-500" xml:space="preserve">
          <g id="XMLID_2_">
            <path id="XMLID_4_" d="M51.213,180h173.785c8.284,0,15-6.716,15-15s-6.716-15-15-15H51.213l19.394-19.393
                  c5.858-5.857,5.858-15.355,0-21.213c-5.856-5.858-15.354-5.858-21.213,0L4.397,154.391c-0.348,0.347-0.676,0.71-0.988,1.09
                  c-0.076,0.093-0.141,0.193-0.215,0.288c-0.229,0.291-0.454,0.583-0.66,0.891c-0.06,0.09-0.109,0.185-0.168,0.276
                  c-0.206,0.322-0.408,0.647-0.59,0.986c-0.035,0.067-0.064,0.138-0.099,0.205c-0.189,0.367-0.371,0.739-0.53,1.123
                  c-0.02,0.047-0.034,0.097-0.053,0.145c-0.163,0.404-0.314,0.813-0.442,1.234c-0.017,0.053-0.026,0.108-0.041,0.162
                  c-0.121,0.413-0.232,0.83-0.317,1.257c-0.025,0.127-0.036,0.258-0.059,0.386c-0.062,0.354-0.124,0.708-0.159,1.069
                  C0.025,163.998,0,164.498,0,165s0.025,1.002,0.076,1.498c0.035,0.366,0.099,0.723,0.16,1.08c0.022,0.124,0.033,0.251,0.058,0.374
                  c0.086,0.431,0.196,0.852,0.318,1.269c0.015,0.049,0.024,0.101,0.039,0.15c0.129,0.423,0.28,0.836,0.445,1.244
                  c0.018,0.044,0.031,0.091,0.05,0.135c0.16,0.387,0.343,0.761,0.534,1.13c0.033,0.065,0.061,0.133,0.095,0.198
                  c0.184,0.341,0.387,0.669,0.596,0.994c0.056,0.088,0.104,0.181,0.162,0.267c0.207,0.309,0.434,0.603,0.662,0.895
                  c0.073,0.094,0.138,0.193,0.213,0.285c0.313,0.379,0.641,0.743,0.988,1.09l44.997,44.997C52.322,223.536,56.161,225,60,225
                  s7.678-1.464,10.606-4.394c5.858-5.858,5.858-15.355,0-21.213L51.213,180z" />
            <path id="XMLID_5_" d="M207.299,42.299c-40.944,0-79.038,20.312-101.903,54.333c-4.62,6.875-2.792,16.195,4.083,20.816
                  c6.876,4.62,16.195,2.794,20.817-4.083c17.281-25.715,46.067-41.067,77.003-41.067C258.414,72.299,300,113.884,300,165
                  s-41.586,92.701-92.701,92.701c-30.845,0-59.584-15.283-76.878-40.881c-4.639-6.865-13.961-8.669-20.827-4.032
                  c-6.864,4.638-8.67,13.962-4.032,20.826c22.881,33.868,60.913,54.087,101.737,54.087C274.956,287.701,330,232.658,330,165
                  S274.956,42.299,207.299,42.299z" />
          </g>
        </svg>
        </Link>
      </div>
    </div>
  </TopBar>

  <SideBar >
    <div class="bg-white shadow-md max-lg:hidden lg:w-60 h-full fixed top-0 transition-all duration-300 z-50 overflow-y-auto" :class="{'!w-20': toggleMenu}">
      <div class="p-4 flex h-16">
        <img class="mx-auto w-100" style="width: 150px; height: 150px; margin-top: -60px;margin-left: 30px;" src="/images/logo_menu.png" alt="" srcset="" >
      </div>
      <hr>
 
      <nav class="mt-6 list-none px-7">
        <div class="uppercase mb-4 text-[10px] font-bold tracking-widest text-gray-400/90">
          <span v-show="!toggleTextMenu">
            Métricas
          </span>
        </div>
        <Link as="button"  :href="route('list.Dashboard')">
        <li class="flex text-gray-500/80 font-semibold space-x-3 items-center text-[14.5px] hover:text-proconph">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
          </svg>
          <span v-show="!toggleTextMenu">Dashboard</span>
        </li>
      </Link>
        <div class="uppercase mt-9 mb-4 text-[10px] font-bold tracking-widest text-gray-400/90" v-if="$page.props.userPermissions.includes('list.ConfigCarros')">
          <span v-show="!toggleTextMenu">
           Gestão de Cadastros
          </span>
        </div>
        
              
        

        <Link as="button" :href="route('list.DashboardCalendario')" v-if="$page.props.userPermissions.includes('list.DashboardCalendario')">
        <li class="flex text-gray-500/80 font-semibold space-x-3 items-center text-[14px] mt-2 hover:text-proconph">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z" />
          </svg>
          <span v-show="!toggleTextMenu">Calendário &nbsp;&nbsp;</span>
        </li>
        </Link>



        <Link as="button" :href="route('list.ConfigCarros')" v-if="$page.props.userPermissions.includes('list.ConfigCarros')">
        <li class="flex text-gray-500/80 font-semibold space-x-3 items-center text-[14px] mt-2 hover:text-proconph">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z" />
          </svg>
          <span v-show="!toggleTextMenu">Carros </span>
        </li>
        </Link>
        <br>
        <Link as="button" :href="route('list.ConfigMotos')" v-if="$page.props.userPermissions.includes('list.ConfigCarros')">
        <li class="flex text-gray-500/80 font-semibold space-x-3 items-center text-[14px] mt-2 hover:text-proconph">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.59 14.37a6 6 0 0 1-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 0 0 6.16-12.12A14.98 14.98 0 0 0 9.631 8.41m5.96 5.96a14.926 14.926 0 0 1-5.841 2.58m-.119-8.54a6 6 0 0 0-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 0 0-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 0 1-2.448-2.448 14.9 14.9 0 0 1 .06-.312m-2.24 2.39a4.493 4.493 0 0 0-1.757 4.306 4.493 4.493 0 0 0 4.306-1.758M16.5 9a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z" />
          </svg>

          <span v-show="!toggleTextMenu">Motos</span>
        </li>
        </Link>

    


        <li class="text-[14px] cursor-pointer mb-3" style="margin-top: 4%">
          <div @click="dropdownConfig = !dropdownConfig" class="flex text-gray-500/80 hover:text-proconph font-semibold space-x-3 items-center"
           v-if="$page.props.userPermissions.includes('list.companies') || $page.props.userPermissions.includes('list.Servicos') || $page.props.userPermissions.includes('list.Motorista') || $page.props.userPermissions.includes('list.Fornecedores') || $page.props.userPermissions.includes('list.Diarias')">
            <div class="w-6 h=6">     
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z" />
              </svg>

            </div>
            <span v-show="!toggleTextMenu">Configurações</span>
            <div class="w-full" v-show="!toggleMenu">
              <svg :class="{'rotate-90': dropdownConfig}" xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 ml-auto transition duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
              </svg>
            </div>
          </div>
          <SlideUpDown v-model="dropdownConfig" :duration="300" v-show="!toggleMenu">
            <ul class="ml-8 mt-2 mb-4 text-gray-400 font-normal space-y-3">
              <Link as="button" :href="route('list.company')" v-if="$page.props.userPermissions.includes('list.companies')">
              <li class="flex space-x-2 items-center hover:text-proconph">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-2 w-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                </svg>
                <span>Empresas &nbsp;&nbsp;</span>
              </li>
              </Link>
                           
            </ul>
          </SlideUpDown>
        </li>

<!-- #ModificaAqui -->
     

        <div class="uppercase mt-9 mb-4 text-[10px] font-bold tracking-widest text-gray-400/90">
          <span v-show="!toggleTextMenu">
            Gestão do Sistema
          </span>
        </div>

        <Link as="button" :href="route('form.update.profile')" >
        <li class="flex text-gray-500/80 font-semibold space-x-3 items-center text-[14px] mt-2 hover:text-proconph">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.182 15.182a4.5 4.5 0 01-6.364 0M21 12a9 9 0 11-18 0 9 9 0 0118 0zM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75zm-.375 0h.008v.015h-.008V9.75zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75zm-.375 0h.008v.015h-.008V9.75z" />
          </svg>
          <span v-show="!toggleTextMenu">Perfil &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
        </li>
        </Link>

        <Link as="button" :href="route('list.users')" v-if="$page.props.userPermissions.includes('list.users')">
        <li class="flex text-gray-500/80 font-semibold space-x-3 items-center text-[14px] mt-2 hover:text-proconph" >
          <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24" fill="currentColor">
            <g>
              <path d="M0,0h24v24H0V0z" fill="none"></path>
            </g>
            <g>
              <g>
                <path d="M4,18v-0.65c0-0.34,0.16-0.66,0.41-0.81C6.1,15.53,8.03,15,10,15c0.03,0,0.05,0,0.08,0.01c0.1-0.7,0.3-1.37,0.59-1.98 C10.45,13.01,10.23,13,10,13c-2.42,0-4.68,0.67-6.61,1.82C2.51,15.34,2,16.32,2,17.35V20h9.26c-0.42-0.6-0.75-1.28-0.97-2H4z"></path>
                <path d="M10,12c2.21,0,4-1.79,4-4s-1.79-4-4-4C7.79,4,6,5.79,6,8S7.79,12,10,12z M10,6c1.1,0,2,0.9,2,2s-0.9,2-2,2 c-1.1,0-2-0.9-2-2S8.9,6,10,6z"></path>
                <path d="M20.75,16c0-0.22-0.03-0.42-0.06-0.63l1.14-1.01l-1-1.73l-1.45,0.49c-0.32-0.27-0.68-0.48-1.08-0.63L18,11h-2l-0.3,1.49 c-0.4,0.15-0.76,0.36-1.08,0.63l-1.45-0.49l-1,1.73l1.14,1.01c-0.03,0.21-0.06,0.41-0.06,0.63s0.03,0.42,0.06,0.63l-1.14,1.01 l1,1.73l1.45-0.49c0.32,0.27,0.68,0.48,1.08,0.63L16,21h2l0.3-1.49c0.4-0.15,0.76-0.36,1.08-0.63l1.45,0.49l1-1.73l-1.14-1.01 C20.72,16.42,20.75,16.22,20.75,16z M17,18c-1.1,0-2-0.9-2-2s0.9-2,2-2s2,0.9,2,2S18.1,18,17,18z"></path>
              </g>
            </g>
          </svg>
          <span v-show="!toggleTextMenu">Usuários &nbsp;&nbsp;&nbsp;&nbsp;</span>
        </li>
        </Link>
          
        <Link as="button" :href="route('list.permission')" v-if="$page.props.userPermissions.includes('list.users') && hasRole">
        <li class="flex text-gray-500/80 font-semibold space-x-3 items-center text-[14px] mt-2 hover:text-proconph">
          <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
            <path d="M0 0h24v24H0V0z" fill="none"></path>
            <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm7 10c0 4.52-2.98 8.69-7 9.93-4.02-1.24-7-5.41-7-9.93V6.3l7-3.11 7 3.11V11zm-11.59.59L6 13l4 4 8-8-1.41-1.42L10 14.17z"></path>
          </svg>
          <span v-show="!toggleTextMenu">Permissões &nbsp;&nbsp;</span>
        </li>
        </Link>
        <Link  as="button" :href="route('list.SMTP')" v-if="$page.props.userPermissions.includes('list.SMTP')">
        <li class="flex text-gray-500/80 font-semibold space-x-3 items-center text-[14px] mt-2 hover:text-proconph">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8.25V18a2.25 2.25 0 002.25 2.25h13.5A2.25 2.25 0 0021 18V8.25m-18 0V6a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 6v2.25m-18 0h18M5.25 6h.008v.008H5.25V6zM7.5 6h.008v.008H7.5V6zm2.25 0h.008v.008H9.75V6z" />
          </svg>
          <span v-show="!toggleTextMenu">SMTP &nbsp;&nbsp;</span>
        </li>
        </Link>



       
           <li class="text-[14px] cursor-pointer mb-3 space-x-3 space-y-3" style="margin-top:8%" > 
          <div @click="dropLogs = !dropLogs" class="flex text-gray-500/80 hover:text-proconph font-semibold space-x-3 items-center" v-if="$page.props.userPermissions.includes('list.logs') || $page.props.userPermissions.includes('list.logsErros')">
            <div class="w-6 h=6">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
              </svg>
            </div>
            <span v-show="!toggleTextMenu">Logs</span>
            <div class="w-full" v-show="!toggleMenu">
              <svg :class="{'rotate-90': dropLogs}" xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 ml-auto transition duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
              </svg>
            </div>
          </div>

          <SlideUpDown v-model="dropLogs" :duration="300" v-show="!toggleMenu">
            <ul class="mt-2 mb-2 text-gray-400 font-normal space-y-3">
              <Link as="button"  :href="route('list.logsUsuario')" v-if="$page.props.userPermissions.includes('list.logs')">
              <li class="flex space-x-1 items-center hover:text-proconph">
                 <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 10.5v3.75m-9.303 3.376C1.83 19.126 2.914 21 4.645 21h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 4.88c-.866-1.501-3.032-1.501-3.898 0L2.697 17.626zM12 17.25h.007v.008H12v-.008z" />
                </svg>
                <span>Rastreabilidade &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
              </li>
              </Link>
              <Link as="button" :href="route('list.logsErros')" v-if="$page.props.userPermissions.includes('list.logsErros')">
              <li class="flex space-x-1 items-center hover:text-proconph">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.05 4.575a1.575 1.575 0 10-3.15 0v3m3.15-3v-1.5a1.575 1.575 0 013.15 0v1.5m-3.15 0l.075 5.925m3.075.75V4.575m0 0a1.575 1.575 0 013.15 0V15M6.9 7.575a1.575 1.575 0 10-3.15 0v8.175a6.75 6.75 0 006.75 6.75h2.018a5.25 5.25 0 003.712-1.538l1.732-1.732a5.25 5.25 0 001.538-3.712l.003-2.024a.668.668 0 01.198-.471 1.575 1.575 0 10-2.228-2.228 3.818 3.818 0 00-1.12 2.687M6.9 7.575V12m6.27 4.318A4.49 4.49 0 0116.35 15m.002 0h-.002" />
               </svg>
                <span>Erros &nbsp;&nbsp; &nbsp;&nbsp;</span>
              </li>
              </Link>
                        
            </ul>
          </SlideUpDown>
          
        </li>
        <div class="uppercase mt-9 mb-4 text-[10px] font-bold tracking-widest text-gray-400/90">
          <span v-show="!toggleTextMenu">
            Aluguel
          </span>
        </div>
        <a as="button" href="/aluguelMotos">
        <li class="flex text-gray-500/80 font-semibold space-x-3 items-center text-[14px] mt-2 hover:text-proconph">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="m3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z" />
          </svg>

          <span v-show="!toggleTextMenu">Alugar Moto &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
        </li>
        </a>
        <a as="button" href="/aluguelCarros">
        <li class="flex text-gray-500/80 font-semibold space-x-3 items-center text-[14px] mt-2 hover:text-proconph">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="m3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z" />
          </svg>

          <span v-show="!toggleTextMenu">Alugar Carro &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
        </li>
        </a>
              <div class="uppercase mt-9 mb-4 text-[10px] font-bold tracking-widest text-gray-400/90">
          <span v-show="!toggleTextMenu">
            Compras
          </span>
        </div>
        <a as="button" href="/vendaMotos">
        <li class="flex text-gray-500/80 font-semibold space-x-3 items-center text-[14px] mt-2 hover:text-proconph">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
</svg>


          <span v-show="!toggleTextMenu">Comprar Moto &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
        </li>
        </a>
        <a as="button" href="/vendaCarros">
        <li class="flex text-gray-500/80 font-semibold space-x-3 items-center text-[14px] mt-2 hover:text-proconph">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
</svg>


          <span v-show="!toggleTextMenu">Comprar Carro &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
        </li>
        </a>
        <div class="uppercase mt-9 mb-4 text-[10px] font-bold tracking-widest text-gray-400/90">
          <span v-show="!toggleTextMenu">
            Consertos
          </span>
        </div>
        <a as="button" href="/conserto">
        <li class="flex text-gray-500/80 font-semibold space-x-3 items-center text-[14px] mt-2 hover:text-proconph">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 0 1 1.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.559.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.894.149c-.424.07-.764.383-.929.78-.165.398-.143.854.107 1.204l.527.738c.32.447.269 1.06-.12 1.45l-.774.773a1.125 1.125 0 0 1-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.398.165-.71.505-.781.929l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.148-.894c-.071-.424-.384-.764-.781-.93-.398-.164-.854-.142-1.204.108l-.738.527c-.447.32-1.06.269-1.45-.12l-.773-.774a1.125 1.125 0 0 1-.12-1.45l.527-.737c.25-.35.272-.806.108-1.204-.165-.397-.506-.71-.93-.78l-.894-.15c-.542-.09-.94-.56-.94-1.109v-1.094c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.765-.383.93-.78.165-.398.143-.854-.108-1.204l-.526-.738a1.125 1.125 0 0 1 .12-1.45l.773-.773a1.125 1.125 0 0 1 1.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.929l.15-.894Z" />
  <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
</svg>


          <span v-show="!toggleTextMenu">Consertar Carro/Moto</span>
        </li>
        </a>
        <br><br><br>
        <li class=" mt-4">
          <Link class="flex text-gray-500/80 font-semibold space-x-3 items-center text-[14px] hover:text-red-400" href="/logout" method="post">
          <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24" fill="currentColor">
            <g>
              <path d="M0,0h24v24H0V0z" fill="none"></path>
            </g>
            <g>
              <path d="M17,8l-1.41,1.41L17.17,11H9v2h8.17l-1.58,1.58L17,16l4-4L17,8z M5,5h7V3H5C3.9,3,3,3.9,3,5v14c0,1.1,0.9,2,2,2h7v-2H5V5z"></path>
            </g>
          </svg>
          <span v-show="!toggleTextMenu">Sair &nbsp;&nbsp;</span>
          </Link>
        </li>
      </nav>
    </div>
  </SideBar>

  <SideBarMobile class="lg:hidden">
    <div class="bg-black/40 fixed top-0 h-full z-30 w-full" v-show="!toggleMenuMobile"></div>
    <button class="left-64 top-3 fixed z-30 text-white" @click="toggleMenuMobile = true" v-show="!toggleMenuMobile">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
      </svg>
    </button>
    <div class="bg-white shadow-md w-60 h-full fixed top-0 z-30 transition-all duration-300" v-show="!toggleMenuMobile">
      <div class="p-4 flex h-16">
        <img class="mx-auto w-100" src="/images/logo_menu.png" alt="" srcset="">
      </div>
      <hr>
      <nav class="mt-6 list-none px-7">
        <div class="uppercase mb-4 text-[10px] font-bold tracking-widest text-gray-400/90">
          <span v-show="!toggleTextMenu">
            Métricas
          </span>
        </div>
        <li class="flex text-gray-500/80 font-semibold space-x-3 items-center text-[14.5px]">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
          </svg>
          <span v-show="!toggleTextMenu">Dashboard</span>
        </li>
        <div class="uppercase mt-9 mb-4 text-[10px] font-bold tracking-widest text-gray-400/90">
          <span v-show="!toggleTextMenu">
           Gestão de Cadastros
          </span>
        </div>
        

       
               

        <Link as="button" :href="route('list.DashboardCalendario')" v-if="$page.props.userPermissions.includes('list.DashboardCalendario')">
        <li class="flex text-gray-500/80 font-semibold space-x-3 items-center text-[14px] mt-2 hover:text-proconph">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z" />
          </svg>
          <span v-show="!toggleTextMenu">Calendário &nbsp;&nbsp;</span>
        </li>
        </Link>

    


        <li class="text-[14px] cursor-pointer mb-3" style="margin-top: 4%">
          <div @click="dropdownConfig = !dropdownConfig" class="flex text-gray-500/80 hover:text-proconph font-semibold space-x-3 items-center"
           v-if="$page.props.userPermissions.includes('list.companies') || $page.props.userPermissions.includes('list.Servicos') || $page.props.userPermissions.includes('list.Motorista') || $page.props.userPermissions.includes('list.Fornecedores') || $page.props.userPermissions.includes('list.Diarias')">
            <div class="w-6 h=6">     
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z" />
              </svg>

            </div>
            <span v-show="!toggleTextMenu">Configurações</span>
            <div class="w-full" v-show="!toggleMenu">
              <svg :class="{'rotate-90': dropdownConfig}" xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 ml-auto transition duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
              </svg>
            </div>
          </div>
          <SlideUpDown v-model="dropdownConfig" :duration="300" v-show="!toggleMenu">
            <ul class="ml-8 mt-2 mb-4 text-gray-400 font-normal space-y-3">
              <Link as="button" :href="route('list.company')" v-if="$page.props.userPermissions.includes('list.companies')">
              <li class="flex space-x-2 items-center hover:text-proconph">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-2 w-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                </svg>
                <span>Empresas &nbsp;&nbsp;</span>
              </li>
              </Link>
                           
            </ul>
          </SlideUpDown>
        </li>

<!-- #ModificaAqui -->
     

        <div class="uppercase mt-9 mb-4 text-[10px] font-bold tracking-widest text-gray-400/90">
          <span v-show="!toggleTextMenu">
            Gestão do Sistema
          </span>
        </div>

        <Link as="button" :href="route('form.update.profile')" >
        <li class="flex text-gray-500/80 font-semibold space-x-3 items-center text-[14px] mt-2">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.182 15.182a4.5 4.5 0 01-6.364 0M21 12a9 9 0 11-18 0 9 9 0 0118 0zM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75zm-.375 0h.008v.015h-.008V9.75zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75zm-.375 0h.008v.015h-.008V9.75z" />
          </svg>
          <span v-show="!toggleTextMenu">Perfil &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
        </li>
        </Link>

        <Link as="button" :href="route('list.users')" v-if="$page.props.userPermissions.includes('list.users')">
        <li class="flex text-gray-500/80 font-semibold space-x-3 items-center text-[14px] mt-2">
          <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24" fill="currentColor">
            <g>
              <path d="M0,0h24v24H0V0z" fill="none"></path>
            </g>
            <g>
              <g>
                <path d="M4,18v-0.65c0-0.34,0.16-0.66,0.41-0.81C6.1,15.53,8.03,15,10,15c0.03,0,0.05,0,0.08,0.01c0.1-0.7,0.3-1.37,0.59-1.98 C10.45,13.01,10.23,13,10,13c-2.42,0-4.68,0.67-6.61,1.82C2.51,15.34,2,16.32,2,17.35V20h9.26c-0.42-0.6-0.75-1.28-0.97-2H4z"></path>
                <path d="M10,12c2.21,0,4-1.79,4-4s-1.79-4-4-4C7.79,4,6,5.79,6,8S7.79,12,10,12z M10,6c1.1,0,2,0.9,2,2s-0.9,2-2,2 c-1.1,0-2-0.9-2-2S8.9,6,10,6z"></path>
                <path d="M20.75,16c0-0.22-0.03-0.42-0.06-0.63l1.14-1.01l-1-1.73l-1.45,0.49c-0.32-0.27-0.68-0.48-1.08-0.63L18,11h-2l-0.3,1.49 c-0.4,0.15-0.76,0.36-1.08,0.63l-1.45-0.49l-1,1.73l1.14,1.01c-0.03,0.21-0.06,0.41-0.06,0.63s0.03,0.42,0.06,0.63l-1.14,1.01 l1,1.73l1.45-0.49c0.32,0.27,0.68,0.48,1.08,0.63L16,21h2l0.3-1.49c0.4-0.15,0.76-0.36,1.08-0.63l1.45,0.49l1-1.73l-1.14-1.01 C20.72,16.42,20.75,16.22,20.75,16z M17,18c-1.1,0-2-0.9-2-2s0.9-2,2-2s2,0.9,2,2S18.1,18,17,18z"></path>
              </g>
            </g>
          </svg>
          <span v-show="!toggleTextMenu">Usuários &nbsp;&nbsp;&nbsp;&nbsp;</span>
        </li>
        </Link>
        <Link as="button" :href="route('list.permission')" v-if="$page.props.userPermissions.includes('list.users')">
        <li class="flex text-gray-500/80 font-semibold space-x-3 items-center text-[14px] mt-2">
          <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
            <path d="M0 0h24v24H0V0z" fill="none"></path>
            <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm7 10c0 4.52-2.98 8.69-7 9.93-4.02-1.24-7-5.41-7-9.93V6.3l7-3.11 7 3.11V11zm-11.59.59L6 13l4 4 8-8-1.41-1.42L10 14.17z"></path>
          </svg>
          <span v-show="!toggleTextMenu">Permissões &nbsp;&nbsp;</span>
        </li>
        </Link>
   
           <li class="text-[14px] cursor-pointer mb-3 space-x-3 space-y-3" style="margin-top:8%" > 
          <div @click="dropLogs = !dropLogs" class="flex text-gray-500/80 hover:text-proconph font-semibold space-x-3 items-center" v-if="$page.props.userPermissions.includes('list.logsUsuario') || $page.props.userPermissions.includes('list.logsErros') || $page.props.userPermissions.includes('list.logs')">
            <div class="w-6 h=6">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
              </svg>
            </div>
            <span v-show="!toggleTextMenu">Logs</span>
            <div class="w-full" v-show="!toggleMenu">
              <svg :class="{'rotate-90': dropLogs}" xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 ml-auto transition duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
              </svg>
            </div>
          </div>

          <SlideUpDown v-model="dropLogs" :duration="300" v-show="!toggleMenu">
            <ul class="mt-2 mb-2 text-gray-400 font-normal space-y-3">
              <Link as="button"  :href="route('list.logsUsuario')" v-if="$page.props.userPermissions.includes('list.logs')">
              <li class="flex space-x-1 items-center hover:text-proconph">
                 <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 10.5v3.75m-9.303 3.376C1.83 19.126 2.914 21 4.645 21h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 4.88c-.866-1.501-3.032-1.501-3.898 0L2.697 17.626zM12 17.25h.007v.008H12v-.008z" />
                </svg>
                <span>Rastreabilidade &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
              </li>
              </Link>
              <Link as="button" :href="route('list.logsErros')" v-if="$page.props.userPermissions.includes('list.logsErros')">
              <li class="flex space-x-1 items-center hover:text-proconph">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.05 4.575a1.575 1.575 0 10-3.15 0v3m3.15-3v-1.5a1.575 1.575 0 013.15 0v1.5m-3.15 0l.075 5.925m3.075.75V4.575m0 0a1.575 1.575 0 013.15 0V15M6.9 7.575a1.575 1.575 0 10-3.15 0v8.175a6.75 6.75 0 006.75 6.75h2.018a5.25 5.25 0 003.712-1.538l1.732-1.732a5.25 5.25 0 001.538-3.712l.003-2.024a.668.668 0 01.198-.471 1.575 1.575 0 10-2.228-2.228 3.818 3.818 0 00-1.12 2.687M6.9 7.575V12m6.27 4.318A4.49 4.49 0 0116.35 15m.002 0h-.002" />
               </svg>
                <span>Erros &nbsp;&nbsp; &nbsp;&nbsp;</span>
              </li>
              </Link>
                        
            </ul>
          </SlideUpDown>
        </li>
        <li class=" mt-4">
          <Link class="flex text-gray-500/80 font-semibold space-x-3 items-center text-[14px]" href="/logout" method="post">
          <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24" fill="currentColor">
            <g>
              <path d="M0,0h24v24H0V0z" fill="none"></path>
            </g>
            <g>
              <path d="M17,8l-1.41,1.41L17.17,11H9v2h8.17l-1.58,1.58L17,16l4-4L17,8z M5,5h7V3H5C3.9,3,3,3.9,3,5v14c0,1.1,0.9,2,2,2h7v-2H5V5z"></path>
            </g>
          </svg>
          <span v-show="!toggleTextMenu">Sair</span>
          </Link>
        </li>
      </nav>
    </div>
  </SideBarMobile>

  <div class="transition-all duration-300 p-6 mt-16" :class="{'lg:ml-60': !toggleMenu, 'lg:ml-20': toggleMenu}">
    <slot />
  </div>
</template>

<script setup>
import { ref, watch } from "vue";
import SlideUpDown from "vue3-slide-up-down";

const dropLogs = ref(false);

const dropdownConfig = ref(false);
const dropdownConfigConf = ref(false);

const dropdownAudit = ref(false);
const dropdownAuditConf = ref(false);

const props = defineProps({
  Usuario: Object,
  hasRole: Boolean,
})
const dropdownReports = ref(false);
const dropdownReportsConf = ref(false);

const dropdownFinanceiro = ref(false);
const dropdownFinanceiroConf = ref(false);

const dropdownMotorista = ref(false);
const dropdownMotoristaConf = ref(false);


const toggleMenu = ref(false);
const toggleTextMenu = ref(false);

const toggleMenuMobile = ref(true);

watch(toggleMenu, () => {
  if (!toggleMenu.value) {
    setTimeout(() => {
      toggleTextMenu.value = toggleMenu.value;
    }, 150);
    return;
  }
  toggleTextMenu.value = toggleMenu.value;
});
</script>


<style scoped>
/* Firefox */
* {
  scrollbar-width: auto;
  scrollbar-color: #d1d1d1 #ffffff;
}

/* Chrome, Edge, and Safari */
*::-webkit-scrollbar {
  width: 12px;
}
/* Chrome, Edge, and Safari */

*::-webkit-scrollbar-track {
  background: #ffffff;
}

*::-webkit-scrollbar-thumb {
  background-color: #d1d1d1;
  border-radius: 10px;
  border: 3px solid #ffffff;
}
</style>

