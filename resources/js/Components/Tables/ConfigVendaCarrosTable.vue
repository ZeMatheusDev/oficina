<template>
  <div class="bg-white px-4 shadow-lg rounded-xl">
    <p><br /></p>
    <div class="flex justify-center items-center space-x-6 py-2">
      <!-- primeiro Card -->
      <div class="bg-white rounded-3xl shadow-2xl w-96 max-h-[250px]">
        <div class="flex flex-col items-center">
          <div class="flex space-x-6 mb-3 mt-2">
            <button
              @click="toggleFilter = !toggleFilter"
              class="p-3 hover:bg-secundary flex space-x-2 items-center cursor-pointer hover:text-white rounded-lg"
              title="Ordenar / Paginação"
            >
              <svg
                class="h-5 w-5"
                viewBox="0 0 24 24"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  d="M10.9404 22.6501C10.4604 22.6501 9.99039 22.5301 9.55039 22.2901C8.67039 21.8001 8.14039 20.9101 8.14039 19.9101V14.6101C8.14039 14.1101 7.81039 13.3601 7.50039 12.9801L3.76039 9.0201C3.13039 8.3901 2.65039 7.3101 2.65039 6.5001V4.2001C2.65039 2.6001 3.86039 1.3501 5.40039 1.3501H18.6004C20.1204 1.3501 21.3504 2.5801 21.3504 4.1001V6.3001C21.3504 7.3501 20.7204 8.5401 20.1304 9.1301L15.8004 12.9601C15.3804 13.3101 15.0504 14.0801 15.0504 14.7001V19.0001C15.0504 19.8901 14.4904 20.9201 13.7904 21.3401L12.4104 22.2301C11.9604 22.5101 11.4504 22.6501 10.9404 22.6501ZM5.40039 2.8501C4.70039 2.8501 4.15039 3.4401 4.15039 4.2001V6.5001C4.15039 6.8701 4.45039 7.5901 4.83039 7.9701L8.64039 11.9801C9.15039 12.6101 9.65039 13.6601 9.65039 14.6001V19.9001C9.65039 20.5501 10.1004 20.8701 10.2904 20.9701C10.7104 21.2001 11.2204 21.2001 11.6104 20.9601L13.0004 20.0701C13.2804 19.9001 13.5604 19.3601 13.5604 19.0001V14.7001C13.5604 13.6301 14.0804 12.4501 14.8304 11.8201L19.1104 8.0301C19.4504 7.6901 19.8604 6.8801 19.8604 6.2901V4.1001C19.8604 3.4101 19.3004 2.8501 18.6104 2.8501H5.40039Z"
                  fill="currentColor"
                />
                <path
                  d="M5.99968 10.75C5.85968 10.75 5.72968 10.71 5.59968 10.64C5.24968 10.42 5.13968 9.95002 5.35968 9.60002L10.2897 1.70002C10.5097 1.35002 10.9697 1.24002 11.3197 1.46002C11.6697 1.68002 11.7797 2.14002 11.5597 2.49002L6.62968 10.39C6.48968 10.62 6.24968 10.75 5.99968 10.75Z"
                  fill="currentColor"
                />
              </svg>
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-3 w-3"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2"
              >
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
              </svg>
              &nbsp; Colunas
            </button>
            <button
              @click="FiltroAvancado = !FiltroAvancado"
              class="ml-4 p-3 hover:bg-secundary flex space-x-2 items-center cursor-pointer hover:text-white rounded-lg"
              title="Filtros Avançados"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="w-5 h-5"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"
                />
              </svg>
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-3 w-3"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2"
              >
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
              </svg>
              &nbsp; Filtros Avançados
            </button>
          </div>
        </div>
      </div>
    </div>
    <p><br /></p>
    <Toolbar>
      <template #start> </template>
      <template #end>
        <SlideUpDown v-model="DeleteSelect" :duration="300">
          <button
            @click="openDelSelect = true"
            class="mt-4 ml-4 p-4 py-3 flex shadow-md bg-red-400 flex space-x-2 items-center cursor-pointer hover:text-white rounded-lg"
            title="Excluir Selecionados"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              stroke-width="1.5"
              stroke="currentColor"
              class="w-5 h-5"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M6 18L18 6M6 6l12 12"
              />
            </svg>
          </button>
        </SlideUpDown>
        <button
          @click="DeleteSelect = !DeleteSelect"
          class="mt-4 ml-4 p-4 py-3 flex shadow-md hover:bg-primary flex space-x-2 items-center cursor-pointer hover:text-white rounded-lg"
          title="Excluir Vários"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor"
            class="w-5 h-5"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m6 4.125l2.25 2.25m0 0l2.25 2.25M12 13.875l2.25-2.25M12 13.875l-2.25 2.25M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"
            />
          </svg>
        </button>

        <button
          @click="openDelTodos = true"
          class="mt-4 ml-4 p-4 py-3 flex shadow-md hover:bg-red-600 flex space-x-2 items-center cursor-pointer hover:text-white rounded-lg"
          title="Excluir Todos"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor"
            class="w-5 h-5"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"
            />
          </svg>
        </button>

        <button
          @click="openRestaurarTodos = true"
          class="mt-4 ml-4 p-4 py-3 flex shadow-md hover:bg-green-400 flex space-x-2 items-center cursor-pointer hover:text-white rounded-lg"
          title="Restaurar Todos Dados Excluídos"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor"
            class="w-5 h-5"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"
            />
          </svg>
        </button>
      </template>
    </Toolbar>

    <SlideUpDown v-model="FiltroAvancado" :duration="300">
      <form @submit.prevent="submit">
        <div class="flex ml-4 grid grid-cols-8 gap-8 max-md:grid-cols-1">
          <div class="col-span-8">
            <h3><b>Filtros Avançados</b></h3>
            <br />
            <hr />
          </div>

          <div>
            <span class="p-float-label">
              <InputText
                v-model="form2.modelo"
                id="modelo"
                type="text"
                class="w-full"
                required
              />
              <label for="modelo" class="text-sm">Modelo</label>
            </span>
          </div>
          <div>
            <span class="p-float-label">
              <InputText
                v-model="form2.placa"
                id="placa"
                type="text"
                class="w-full"
                required
              />
              <label for="placa" class="text-sm">Placa</label>
            </span>
          </div>
          <div>
						<span class="p-float-label">
							<InputText v-model="form2.valor_para_venda" id="valor_para_venda" type="text" class="w-full" required />
							<label for="valor_para_venda" class="text-sm">Valor do carro</label>
						</span>
					</div>
          <div>
            <span class="p-float-label">
              <InputText
                v-model="form2.marca"
                id="marca"
                type="text"
                class="w-full"
                required
              />
              <label for="marca" class="text-sm">Marca</label>
            </span>
          </div>
          <div>
            <span class="p-float-label">
              <InputText
                v-model="form2.ano"
                id="ano"
                type="text"
                class="w-full"
                required
              />
              <label for="ano" class="text-sm">Ano</label>
            </span>
          </div>
          <div>
            <span class="p-float-label">
              <InputText
                v-model="form2.cor"
                id="cor"
                type="text"
                class="w-full"
                required
              />
              <label for="cor" class="text-sm">Cor</label>
            </span>
          </div>
          <div>
            <span class="p-float-label">
              <InputText
                v-model="form2.observacao"
                id="observacao"
                type="text"
                class="w-full"
                required
              />
              <label for="observacao" class="text-sm">Observação</label>
            </span>
          </div>
          <div>
            <span class="p-float-label">
              <Checkbox
                inputId="binary"
                v-model="form2.limparFiltros"
                :binary="true"
                style="margin: 2%"
              />
              <label for="status" class="text-sm" style="margin-left: 5%"
                >Deseja Resetar o Filtro Aplicado?</label
              >
            </span>
          </div>
        </div>

        <div class="flex ml-4 space-x-5 mt-8">
          <button
            @click="FiltroAvancadoAplica"
            class="p-2 flex rounded-md bg-primary text-white px-6 text-sm font-medium items-center"
          >
            Aplicar
          </button>
        </div>
      </form>
    </SlideUpDown>

    <SlideUpDown v-model="toggleFilter" :duration="300">
      <div class="flex mb-4 max-md:flex-col-reverse items-center">
        <Limit
          class="p-field p-col-12 p-md-4 mt-6 max-md:w-full max-md:mb-5 flex items-center space-x-3 text-xs text-gray-500"
        >
          <span>Mostrar</span>
          <select
            name=""
            @change="setParams"
            v-model="recordValue"
            id=""
            class="rounded-md focus:ring-primary h-8 text-xs w-20"
          >
            <option value="10">10</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="100">100</option>
            <option value="500">500</option>
          </select>
          <span>registros</span>
        </Limit>
      </div>
      <hr class="my-4" />
      <div class="text-sm text-gray-500 flex items-center">
        <div class="space-x-6 flex ml-4 grid grid-cols-6 gap-6 max-md:grid-cols-1">
          <span class="font-semibold">Colunas Visíveis:</span>

          <div class="flex items-center space-x-2">
            <Checkbox
              @change="toggleColumns"
              :binary="true"
              v-model="formColumns['columns']['modelo']"
            />
            <span>Modelo</span>
          </div>
          <div class="flex items-center space-x-2">
            <Checkbox
              @change="toggleColumns"
              :binary="true"
              v-model="formColumns['columns']['placa']"
            />
            <span>Placa</span>
          </div>
          <div class="flex items-center space-x-2">
            <Checkbox
              @change="toggleColumns"
              :binary="true"
              v-model="formColumns['columns']['valor_para_venda']"
            />
            <span>Valor da diaria</span>
          </div>
          <div class="flex items-center space-x-2">
            <Checkbox
              @change="toggleColumns"
              :binary="true"
              v-model="formColumns['columns']['marca']"
            />
            <span>Marca</span>
          </div>
          <div class="flex items-center space-x-2">
            <Checkbox
              @change="toggleColumns"
              :binary="true"
              v-model="formColumns['columns']['ano']"
            />
            <span>Ano</span>
          </div>
          <div class="flex items-center space-x-2">
            <Checkbox
              @change="toggleColumns"
              :binary="true"
              v-model="formColumns['columns']['cor']"
            />
            <span>Cor</span>
          </div>

          <div class="flex items-center space-x-2">
            <Checkbox
              @change="toggleColumns"
              :binary="true"
              v-model="formColumns['columns']['observacao']"
            />
            <span>Observação</span>
          </div>
          <div class="flex items-center space-x-2">
            <Checkbox
              @change="toggleColumns"
              :binary="true"
              v-model="formColumns['columns']['created_at']"
            />
            <span>Data De Cadastro</span>
          </div>
        </div>
      </div>
    </SlideUpDown>

    <p>
      <br />
    </p>

    <div class="mt-4 flex flex-col max-md:px-2 py-1 rounded-lg shadow-sm">
      <div class="inline-block min-w-full py-2 align-middle">
        <h2 class="text-xl font-semibold fontInter text-center py-5"><b>Carros</b></h2>
        <div
          class="overflow-hidden overflow-x-visible ring-1 ring-black ring-opacity-5 md:rounded-lg"
        >
          <table class="min-w-full divide-y divide-gray-300">
            <thead class="mb-24">
              <tr class="text-gray-500 font-bold select-none" @click="setParams">
                <th
                  scope="col"
                  class="px-4 text-sm cursor-pointer text-center border-r group"
                  style="width: 2%"
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="w-6 h-6"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m6 4.125l2.25 2.25m0 0l2.25 2.25M12 13.875l2.25-2.25M12 13.875l-2.25 2.25M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"
                    />
                  </svg>
                </th>

                <th
                  v-if="formColumns.columns.modelo"
                  scope="col"
                  class="px-4 text-sm cursor-pointer text-center border-r group"
                  @click="
                    orderBy = {
                      column: 'modelo',
                      sorting: sortTable(sortVal.modelo)
                        ? (sortVal.modelo = 1)
                        : (sortVal.modelo = 0),
                    }
                  "
                >
                  <div class="flex">
                    <span class="group-hover:text-indigo-800">Modelo</span>
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      class="h-5 w-5 ml-auto group-hover:text-indigo-800"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                      stroke-width="2"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"
                      />
                    </svg>
                  </div>
                </th>
                <th
                  v-if="formColumns.columns.placa"
                  scope="col"
                  class="px-4 text-sm cursor-pointer text-center border-r group"
                  @click="
                    orderBy = {
                      column: 'placa',
                      sorting: sortTable(sortVal.placa)
                        ? (sortVal.placa = 1)
                        : (sortVal.placa = 0),
                    }
                  "
                >
                  <div class="flex">
                    <span class="group-hover:text-indigo-800">Placa</span>
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      class="h-5 w-5 ml-auto group-hover:text-indigo-800"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                      stroke-width="2"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"
                      />
                    </svg>
                  </div>
                </th>
                <th
                  v-if="formColumns.columns.valor_para_venda"
                  scope="col"
                  class="px-4 text-sm cursor-pointer text-center border-r group"
                  @click="
                    orderBy = {
                      column: 'valor_para_venda',
                      sorting: sortTable(sortVal.valor_para_venda)
                        ? (sortVal.valor_para_venda = 1)
                        : (sortVal.valor_para_venda = 0),
                    }
                  "
                >
                  <div class="flex">
                    <span class="group-hover:text-indigo-800">Valor do carro</span>
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      class="h-5 w-5 ml-auto group-hover:text-indigo-800"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                      stroke-width="2"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"
                      />
                    </svg>
                  </div>
                </th>
                <th
                  v-if="formColumns.columns.marca"
                  scope="col"
                  class="px-4 text-sm cursor-pointer text-center border-r group"
                  @click="
                    orderBy = {
                      column: 'marca',
                      sorting: sortTable(sortVal.marca)
                        ? (sortVal.marca = 1)
                        : (sortVal.marca = 0),
                    }
                  "
                >
                  <div class="flex">
                    <span class="group-hover:text-indigo-800">Marca</span>
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      class="h-5 w-5 ml-auto group-hover:text-indigo-800"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                      stroke-width="2"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"
                      />
                    </svg>
                  </div>
                </th>
                <th
                  v-if="formColumns.columns.ano"
                  scope="col"
                  class="px-4 text-sm cursor-pointer text-center border-r group"
                  @click="
                    orderBy = {
                      column: 'ano',
                      sorting: sortTable(sortVal.ano)
                        ? (sortVal.ano = 1)
                        : (sortVal.ano = 0),
                    }
                  "
                >
                  <div class="flex">
                    <span class="group-hover:text-indigo-800">Ano</span>
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      class="h-5 w-5 ml-auto group-hover:text-indigo-800"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                      stroke-width="2"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"
                      />
                    </svg>
                  </div>
                </th>
                <th
                  v-if="formColumns.columns.cor"
                  scope="col"
                  class="px-4 text-sm cursor-pointer text-center border-r group"
                  @click="
                    orderBy = {
                      column: 'cor',
                      sorting: sortTable(sortVal.cor)
                        ? (sortVal.cor = 1)
                        : (sortVal.cor = 0),
                    }
                  "
                >
                  <div class="flex">
                    <span class="group-hover:text-indigo-800">Cor</span>
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      class="h-5 w-5 ml-auto group-hover:text-indigo-800"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                      stroke-width="2"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"
                      />
                    </svg>
                  </div>
                </th>
                
                <th
                  v-if="formColumns.columns.observacao"
                  scope="col"
                  class="px-4 text-sm cursor-pointer text-center border-r group"
                  @click="
                    orderBy = {
                      column: 'observacao',
                      sorting: sortTable(sortVal.observacao)
                        ? (sortVal.observacao = 1)
                        : (sortVal.observacao = 0),
                    }
                  "
                >
                  <div class="flex">
                    <span class="group-hover:text-indigo-800">Observação</span>
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      class="h-5 w-5 ml-auto group-hover:text-indigo-800"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                      stroke-width="2"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"
                      />
                    </svg>
                  </div>
                </th>
                <th
                  v-if="formColumns.columns.status"
                  scope="col"
                  class="px-4 text-sm cursor-pointer text-center border-r group"
                  @click="
                    orderBy = {
                      column: 'status',
                      sorting: sortTable(sortVal.status)
                        ? (sortVal.status = 1)
                        : (sortVal.status = 0),
                    }
                  "
                >
                  <div class="flex">
                    <span class="group-hover:text-indigo-800">Status</span>
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      class="h-5 w-5 ml-auto group-hover:text-indigo-800"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                      stroke-width="2"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"
                      />
                    </svg>
                  </div>
                </th>
                <th
                  v-if="formColumns.columns.created_at"
                  scope="col"
                  class="px-4 text-sm cursor-pointer text-center border-r group"
                  @click="
                    orderBy = {
                      column: 'created_at',
                      sorting: sortTable(sortVal.created_at)
                        ? (sortVal.created_at = 1)
                        : (sortVal.created_at = 0),
                    }
                  "
                >
                  <div class="flex">
                    <span class="group-hover:text-indigo-800">Data De Cadastro</span>
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      class="h-5 w-5 ml-auto group-hover:text-indigo-800"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                      stroke-width="2"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"
                      />
                    </svg>
                  </div>
                </th>

				<th scope="col" class="px-3 py-3.5 text-center text-sm sm:pr-12" style="width: 5%;">
									Comprar</th>
								</tr>

            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
              <tr
                v-for="(data, key) in dataTable?.data"
                :key="key"
                class="hover:bg-indigo-50/20"
                :class="{ 'bg-gray-50': key % 2 }"
              >
                <td class="whitespace-nowrap py-6 pl-4 pr-3 text-sm sm:pl-6">
                  <div class="flex items-center">
                    <div>
                      <SlideUpDown v-model="DeleteSelect" :duration="300">
                        <Checkbox
                          inputId="id"
                          name="selected"
                          :value="data?.token"
                          v-model="selected"
                        />
                      </SlideUpDown>
                    </div>
                  </div>
                </td>

                <td
                  v-if="formColumns?.columns?.modelo"
                  class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6"
                >
                  <div class="flex items-center">
                    <div>
                      <div class="font-medium text-gray-900">{{ data?.modelo }}</div>
                    </div>
                  </div>
                </td>
                <td
                  v-if="formColumns?.columns?.placa"
                  class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6"
                >
                  <div class="flex items-center">
                    <div>
                      <div class="font-medium text-gray-900">{{ data?.placa }}</div>
                    </div>
                  </div>
                </td>
                <td
                  v-if="formColumns?.columns?.valor_para_venda"
                  class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6"
                >
                  <div class="flex items-center">
                    <div>
                      <div class="font-medium text-gray-900">R${{ data?.valor_para_venda }}</div>
                    </div>
                  </div>
                </td>
                <td
                  v-if="formColumns?.columns?.marca"
                  class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6"
                >
                  <div class="flex items-center">
                    <div>
                      <div class="font-medium text-gray-900">{{ data?.marca }}</div>
                    </div>
                  </div>
                </td>
                <td
                  v-if="formColumns?.columns?.ano"
                  class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6"
                >
                  <div class="flex items-center">
                    <div>
                      <div class="font-medium text-gray-900">{{ data?.ano }}</div>
                    </div>
                  </div>
                </td>
                <td
                  v-if="formColumns?.columns?.cor"
                  class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6"
                >
                  <div class="flex items-center">
                    <div>
                      <div class="font-medium text-gray-900">{{ data?.cor }}</div>
                    </div>
                  </div>
                </td>
                <td
                  v-if="formColumns?.columns?.observacao"
                  class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6"
                >
                  <div class="flex items-center">
                    <div>
                      <div class="font-medium text-gray-900">{{ data?.observacao }}</div>
                    </div>
                  </div>
                </td>
                
                <td
                  v-if="formColumns?.columns?.status"
                  class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 text-center"
                >
                  <span
                    class="inline-flex rounded-full bg-green-100 text-center px-4 text-xs font-semibold leading-5 text-green-800"
                    v-if="data?.status == '0'"
                    >Ativo</span
                  >
                  <span
                    class="inline-flex rounded-full bg-red-100 text-center px-4 text-xs font-semibold leading-5 text-red-800"
                    v-if="data?.status == '1'"
                    >Inativo</span
                  >
                </td>
                <td
                  v-if="formColumns?.columns?.created_at"
                  class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6"
                >
                  <div class="flex items-center">
                    <div>
                      <div class="font-medium text-gray-900">{{ data?.data_final }}</div>
                    </div>
                  </div>
                </td>

                <td
                  class="whitespace-nowrap py-4 pl-3 pr-4 text-center text-sm font-medium sm:pr-6"
                  style="width: 5%"
                >

				<td class="whitespace-nowrap py-4 pl-3 pr-4 text-center text-sm font-medium sm:pr-6" style="width: 5%;">
									<Comprar 
									:routeComprar="route('comprandoCarro', { id: data?.token })"
									/>	
								</td>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <Pagination
      :links="dataTable"
      :orderBy="props.Filtros.orderBy"
      :limit="props.Filtros.limit"
    />
    <Delete v-model:open="openDelTodos" @del="delTodos" />
    <Delete v-model:open="openDelSelect" @del="del" />
    <Restaurar v-model:open="openRestaurarTodos" @del="RestaurarTodos" />
  </div>
</template>

<script setup>
import Message from "../../Layouts/Message.vue";
import Checkbox from "primevue/checkbox";
import Toolbar from "primevue/toolbar";
import SlideUpDown from "vue3-slide-up-down";
import Button from "primevue/button";
import InputText from "primevue/inputtext";
import Textarea from "primevue/textarea";
import MultiSelect from "primevue/multiselect";
import Dropdown from "primevue/dropdown";
import ColorPicker from "primevue/colorpicker";
import InputSwitch from "primevue/inputswitch";
import Pagination from "../Pagination.vue";
import Comprar from "./ComprarCarro.vue";
import Delete from "../Modals/Delete.vue";
import Restaurar from "../Modals/Restaurar.vue";
import { Link } from "@inertiajs/inertia-vue3";
import { ref, defineProps, watch } from "vue";
import { Inertia } from "@inertiajs/inertia";
import { useForm } from "@inertiajs/inertia-vue3";
import AchievementCard from "../../Layouts/CardsListagens.vue";
const _ = require("lodash");

const props = defineProps({
  dataTable: Object,

  Filtros: Object,
  Registros: Object,
});

const showDeleteModal = ref([]);
const openDelSelect = ref(false);
const openDelTodos = ref(false);
const openRestaurarTodos = ref(false);

const searchBy = ref(getParams("searchBy") || "");

const statusValue = ref(getParams("byStatus"));

const status = [
  { label: "Ativo", code: "0" },
  { label: "Inativo", code: "1" },
];

const recordValue = ref(getParams("limit") || 10);

const orderBy = ref(["column", "sorting"]);

const toggleFilter = ref(false);
const FiltroAvancado = ref(false);
const DeleteSelect = ref(false);
const checked = ref(false);
const selected = ref([]);
const valor = ref([]);

const statusOption = [
  { name: "Ativo", value: "0" },
  { name: "Inativo", value: "1" },
];

const sortVal = {
  modelo: 1,
  placa: 1,
  valor_para_venda: 1,
  marca: 1,
  ano: 1,
  cor: 1,
  observacao: 1,
  status: 1,
  created_at: 1,
};

const formColumns = useForm({
  route_of_list: "list.ConfigCarros",
  columns: {
    modelo: validateColumnsVisibility("modelo"),
    placa: validateColumnsVisibility("placa"),
    valor_para_venda: validateColumnsVisibility("valor_para_venda"),
    marca: validateColumnsVisibility("marca"),
    ano: validateColumnsVisibility("ano"),
    cor: validateColumnsVisibility("cor"),
    observacao: validateColumnsVisibility("observacao"),
    status: validateColumnsVisibility("status"),
    created_at: validateColumnsVisibility("created_at"),
  },
});

const form2 = useForm({
  modelo: props.Filtros?.modelo || null,
  placa: props.Filtros?.placa || null,
  valor_para_venda: props.Filtros?.valor_para_venda || null,
  marca: props.Filtros?.marca || null,
  ano: props.Filtros?.ano || null,
  cor: props.Filtros?.cor || null,
  observacao: props.Filtros?.observacao || null,
  status: props.Filtros?.status || null,
  created_at: props.Filtros?.created_at || null,
  limparFiltros: "",
});

function validateColumnsVisibility(column) {
  let columnValue = Inertia.page.props.columnsTable?.[column];
  if (typeof columnValue == "boolean") {
    return columnValue;
  }
  return true;
}

function toggleColumns() {
  formColumns.post(route("toggle.columns.tables"), {
    preserveState: true,
  });
}
function sortTable(sort) {
  if (sort) {
    return 0;
  } else {
    return 1;
  }
}

function hasFilterActived() {
  if (
    getParams("searchBy") !== null ||
    getParams("limit") !== null ||
    getParams("orderBy") !== null
  ) {
    return true;
  }
  return false;
}

function resetFilter() {
  window.history.replaceState(null, null, window.location.pathname);
  recordValue.value = 10;
  searchBy.value = "";
  Inertia.reload();
}

function del() {
  valor.value = selected.value;
  selected.value = "";
  Inertia.post(route("deleteSelected.ConfigCarros", { id: valor.value }));
}
function delTodos() {
  Inertia.post(route("deletarTodos.ConfigCarros"));
}

function RestaurarTodos() {
  Inertia.post(route("RestaurarTodos.ConfigCarros"));
}

function getFormFiltered() {
  const newForm = {};
  for (let [key, value] of Object.entries(form2)) {
    if (typeof value == "object" && value?.value) {
      newForm[key] = value.value;
    } else {
      newForm[key] = value;
    }
  }
  return newForm;
}

function setParams() {
  let data = {
    limit: recordValue?.value,
    searchBy: searchBy.value,
    byStatus: statusValue?.value?.value,
  };
  !orderBy.value?.length ? (data.orderBy = orderBy?.value) : "";
  Inertia.visit("", {
    preserveState: true,
    replace: false,
    data,
  });
}

function FiltroAvancadoAplica() {
  const submitForm = getFormFiltered();
  let data = {
    submitForm,
  };
  !orderBy.value?.length ? (data.orderBy = orderBy?.value) : "";
  submitForm.post(route("listP.ConfigCarros"), {
    replace: false,
    data,
    onSuccess: () => {
      (FiltroAvancado.value = false), window.location.reload(); // recarrega a página após a atualização
    },
  });
}

watch(
  () => props.Filtros,
  (novoValor, valorAntigo) => {
    let OrderBy = "";
    let MeuLimit = recordValue.value;
    if (novoValor !== valorAntigo) {
      if (props.Filtros.orderBy) {
        OrderBy = `&orderBy[column]=${props.Filtros.orderBy.column}&orderBy[sorting]=${props.Filtros.orderBy.sorting}`;
      }
      if (props.Filtros.limit) {
        MeuLimit = props.Filtros.limit;
      }
      const url = `${window.location.pathname}?page=1&limit=${MeuLimit}${OrderBy}`;
      window.location.replace(url);
    }
  }
);
</script>
