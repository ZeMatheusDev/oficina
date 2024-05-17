Primeiro Passo: (Web.php) 

    NO TOPO: use App\Http\Controllers\@@TROCARAQUI@@@;

    Route::get('@@TROCARAQUI@@@', [@@TROCARAQUI@@@::class, 'index'])->name('list.@@TROCARAQUI@@@');
	Route::post('@@TROCARAQUI@@@', [@@TROCARAQUI@@@::class, 'index'])->name('listP.@@TROCARAQUI@@@');
    Route::get('@@TROCARAQUI@@@/criar', [@@TROCARAQUI@@@::class, 'create'])->name('form.store.@@TROCARAQUI@@@');
    Route::post('@@TROCARAQUI@@@/criar', [@@TROCARAQUI@@@::class, 'store'])->name('store.@@TROCARAQUI@@@');
    Route::get('@@TROCARAQUI@@@/editar/{id}', [@@TROCARAQUI@@@::class, 'edit'])->name('form.update.@@TROCARAQUI@@@');
    Route::post('@@TROCARAQUI@@@/editar/{id}', [@@TROCARAQUI@@@::class, 'update'])->name('update.@@TROCARAQUI@@@');
    Route::post('@@TROCARAQUI@@@/deletar/{id}', [@@TROCARAQUI@@@::class, 'delete'])->name('delete.@@TROCARAQUI@@@');
	Route::post('@@TROCARAQUI@@@/deletarSelecionados/{id?}', [@@TROCARAQUI@@@::class, 'deleteSelected'])->name('deleteSelected.@@TROCARAQUI@@@');
	Route::post('@@TROCARAQUI@@@/deletarTodos', [@@TROCARAQUI@@@::class, 'deletarTodos'])->name('deletarTodos.@@TROCARAQUI@@@');
	Route::post('@@TROCARAQUI@@@/RestaurarTodos', [@@TROCARAQUI@@@::class, 'RestaurarTodos'])->name('RestaurarTodos.@@TROCARAQUI@@@');
	Route::get('@@TROCARAQUI@@@/RelatorioExcel', [@@TROCARAQUI@@@::class, 'exportarRelatorioExcel'])->name('get.Excel.@@TROCARAQUI@@@');


Segundo Passo: 

    Copie e cole o controller modelo na pasta de controllers e coloque o mesmo nome que você usou no @@TROCARAQUI@@@


Terceiro Passo: 

    Criar as Permissões no Banco de dados em utilizando esse sql e substituindo o @@TROCARAQUI@@@ pelo nome correto e onde tem 125 verificar no banco de dados qual o proximo numero da sequencia 

    INSERT INTO `permissions` (`name`, `guard_name`, `ordem`, `created_at`, `updated_at`) VALUES ('list.@@TROCARAQUI@@@', 'web', 125, NULL, NULL);
    INSERT INTO `permissions` (`name`, `guard_name`, `ordem`, `created_at`, `updated_at`) VALUES ('create.@@TROCARAQUI@@@', 'web', 125, NULL, NULL);
    INSERT INTO `permissions` (`name`, `guard_name`, `ordem`, `created_at`, `updated_at`) VALUES ('edit.@@TROCARAQUI@@@', 'web', 125, NULL, NULL);
    INSERT INTO `permissions` (`name`, `guard_name`, `ordem`, `created_at`, `updated_at`) VALUES ('duplicate.@@TROCARAQUI@@@', 'web', 125, NULL, NULL);
    INSERT INTO `permissions` (`name`, `guard_name`, `ordem`, `created_at`, `updated_at`) VALUES ('delete.@@TROCARAQUI@@@', 'web', 125, NULL, NULL);

Quarto Passo: Colar esse codigo no Permission.vue logo apos o Modifica aqui, trocar o @@TROCARAQUI@@@ pelo nome correto e os numeros com os IDs que foi gerado apos seu insert acima.
            Onde A ate E serão os IDs da sequencia acima
            ORDEM  sera a ordem definida no quinto passo

    <tr>
		<td class='p-2 whitespace-nowrap'>
		  <div class='flex items-center'>
			<div class='font-medium text-gray-800'>@@TROCARAQUI@@@</div>
		  </div>
		</td>
		<td class='p-2 text-center'>
		   <Checkbox @click='markLine(range(A, E), ORDEM)' v-model='lineChecked' class='ml-auto' :value='125' />
		</td>
		<td class='p-2 whitespace-nowrap text-center'>
		<Checkbox v-model='checked' :value='A' />
		</td>
		<td class='p-2 whitespace-nowrap text-center'>
		<Checkbox v-model='checked' :value='B' />
		  
		</td>
		<td class='p-2 whitespace-nowrap text-center'>
		<Checkbox v-model='checked' :value='C' />
		  
		</td>
		<td class='p-2 whitespace-nowrap text-center'>
		<Checkbox v-model='checked' :value='D' />
		  
		</td>
		<td class='p-2 whitespace-nowrap text-center'>
		<Checkbox v-model='checked' :value='E' />
		  
		</td>
	  </tr>

Quinto Passo: 

    Crie a Listagem em Componentes/Table/ com @@TROCARAQUI@@@Table.vue
    Pode usar o modelo que esta disponivel, lmebrando que tem que trocar o nome.

Sexto Passo: 

    Criar a pasta das views em resourcers/js com o mesmo no do @@TROCARAQUI@@@
    Dentro dessa pasta deverá ter inicialmente create, edit e o list.
    Voce pode usar os modelos disponibilizado, usando o control + H para substituir @@TROCARAQUI@@@ localizado no modelo pelo nome correto





