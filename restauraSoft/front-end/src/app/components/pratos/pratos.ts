import {Component, OnInit} from '@angular/core';
import {Prato, PratoRequest, PratosService} from '../../services/pratos/pratos.service';
import {Categoria, CategoriasService} from '../../services/categorias/categorias.service';
import {FormBuilder, FormGroup, FormsModule, ReactiveFormsModule, Validators} from '@angular/forms';
import {ToastrService} from 'ngx-toastr';
import {MatIconModule} from '@angular/material/icon';
import {CommonModule} from '@angular/common';
import {fadeSlide} from '../../animations/FadeSlide';
import {itemAnimation} from '../../animations/ItemAnimation';
import {Observable} from 'rxjs';

@Component({
  selector: 'app-pratos',
  imports: [
    MatIconModule,
    CommonModule,
    FormsModule,
    ReactiveFormsModule
  ],
  templateUrl: './pratos.html',
  styleUrl: './pratos.css',
  animations: [fadeSlide, itemAnimation]
})
export class Pratos implements OnInit{
  pratos: Prato[] = [];
  categorias: Categoria[] = [];

  // Estados do modal
  isEditModalOpen = false;
  isDeleteModalOpen = false;
  selectedPrato: Prato | null = null;
  isLoading = false;

  // Formulário reativo
  pratoForm!: FormGroup;
  imagemPreview: string | ArrayBuffer | null = null;
  imagemFile: File | null = null;
  imagemAtual: string | null = null; // Para controlar se tem imagem atual

  constructor(
    private pratoService: PratosService,
    private categoriaService: CategoriasService,
    private toastr: ToastrService,
    private fb: FormBuilder
  ) {
    this.initForm();
  }

  ngOnInit(): void {
    this.loadPratos();
    this.loadCategorias();
  }

  initForm(): void {
    this.pratoForm = this.fb.group({
      nome: ['', [Validators.required, Validators.minLength(3)]],
      descricao: [''],
      preco: [0, [Validators.required, Validators.min(0.01)]],
      categoria_id: ['', [Validators.required]],
      ativo: [true]
      // Removemos o campo imagem do formGroup
    });
  }

  loadPratos(): void {
    this.isLoading = true;
    this.pratoService.getPratos().subscribe({
      next: (pratos) => {
        this.pratos = pratos;
        this.isLoading = false;
      },
      error: (error) => {
        console.error('Erro ao carregar pratos:', error);
        this.toastr.error('Erro ao carregar pratos', 'Erro');
        this.isLoading = false;
      }
    });
  }

  loadCategorias(): void {
    this.categoriaService.getCategorias().subscribe({
      next: (categorias) => {
        this.categorias = categorias.filter(c => c.ativo);
      },
      error: (error) => {
        console.error('Erro ao carregar categorias:', error);
        this.toastr.error('Erro ao carregar categorias', 'Erro');
      }
    });
  }

  getCategoriaName(categoriaId: number): string {
    const categoria = this.categorias.find(c => c.id === categoriaId);
    return categoria?.nome || 'Sem categoria';
  }

  handleNovoPrato(): void {
    this.selectedPrato = null;
    this.pratoForm.reset({
      nome: '',
      descricao: '',
      preco: 0,
      categoria_id: '',
      ativo: true
    });
    this.imagemPreview = null;
    this.imagemFile = null;
    this.imagemAtual = null;
    this.isEditModalOpen = true;
  }

  handleEditPrato(prato: Prato): void {
    this.selectedPrato = prato;
    this.pratoForm.patchValue({
      nome: prato.nome,
      descricao: prato.descricao || '',
      preco: prato.preco,
      categoria_id: prato.categoria_id,
      ativo: prato.ativo
    });

    // Usa a imagem_url se disponível, senão usa a imagem normal
    this.imagemPreview = prato.imagem_url || prato.imagem || null;
    this.imagemAtual = prato.imagem_url || prato.imagem || null;
    this.imagemFile = null;
    this.isEditModalOpen = true;
  }

  handleDeletePrato(prato: Prato): void {
    this.selectedPrato = prato;
    this.isDeleteModalOpen = true;
  }

  onFileSelected(event: any): void {
    const file = event.target.files[0];
    if (file) {
      // Validação do tipo de arquivo
      const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
      if (!allowedTypes.includes(file.type)) {
        this.toastr.error('Formato de imagem inválido. Use JPG, PNG ou GIF.', 'Erro');
        return;
      }

      // Validação do tamanho (2MB máximo)
      if (file.size > 2 * 1024 * 1024) {
        this.toastr.error('A imagem não pode ser maior que 2MB.', 'Erro');
        return;
      }

      this.imagemFile = file;

      // Criar preview da imagem
      const reader = new FileReader();
      reader.onload = () => {
        this.imagemPreview = reader.result;
      };
      reader.readAsDataURL(file);
    }
  }

  handleSavePrato(): void {
    if (this.pratoForm.invalid) {
      this.toastr.warning('Por favor, preencha os campos obrigatórios', 'Atenção');
      return;
    }

    this.isLoading = true;

    const formValue = this.pratoForm.value;

    const pratoData: PratoRequest = {
      nome: formValue.nome,
      descricao: formValue.descricao || '',
      preco: formValue.preco,
      categoria_id: formValue.categoria_id,
      ativo: formValue.ativo,
      imagem: this.imagemAtual || undefined
    };

    console.log('Dados do prato:', pratoData);
    console.log('Arquivo de imagem:', this.imagemFile);

    let request: Observable<any>;

    if (this.selectedPrato) {
      request = this.pratoService.updatePrato(
        this.selectedPrato.id,
        pratoData,
        this.imagemFile || undefined
      );
    } else {
      request = this.pratoService.createPrato(
        pratoData,
        this.imagemFile || undefined
      );
    }

    request.subscribe({
      next: (response) => {
        this.toastr.success(
          this.selectedPrato
            ? 'Prato atualizado com sucesso!'
            : 'Prato criado com sucesso!',
          'Sucesso'
        );
        this.loadPratos();
        this.isEditModalOpen = false;
        this.isLoading = false;
      },
      error: (error) => {
        console.error('Erro ao salvar prato:', error);

        if (error.status === 409) {
          this.toastr.error('Já existe um prato com este nome', 'Erro');
        } else if (error.status === 400) {
          if (error.error?.message?.includes('imagem')) {
            this.toastr.error('Imagem inválida. Verifique o formato e tamanho.', 'Erro');
          } else if (error.error?.message?.includes('Categoria')) {
            this.toastr.error('Categoria inválida', 'Erro');
          } else {
            this.toastr.error(error.error?.message || 'Erro na requisição', 'Erro');
          }
        } else {
          this.toastr.error('Erro ao salvar prato', 'Erro');
        }

        this.isLoading = false;
      }
    });
  }

  handleConfirmDelete(): void {
    if (!this.selectedPrato) return;

    this.isLoading = true;
    this.pratoService.deletePrato(this.selectedPrato.id).subscribe({
      next: () => {
        this.toastr.success('Prato excluído com sucesso!', 'Sucesso');
        this.loadPratos();
        this.isDeleteModalOpen = false;
        this.isLoading = false;
      },
      error: (error) => {
        console.error('Erro ao excluir prato:', error);
        this.toastr.error('Erro ao excluir prato', 'Erro');
        this.isLoading = false;
      }
    });
  }

  closeEditModal(): void {
    this.isEditModalOpen = false;
    this.selectedPrato = null;
    this.imagemPreview = null;
    this.imagemFile = null;
    this.imagemAtual = null;
    this.pratoForm.reset({
      nome: '',
      descricao: '',
      preco: 0,
      categoria_id: '',
      ativo: true
    });
  }

  closeDeleteModal(): void {
    this.isDeleteModalOpen = false;
    this.selectedPrato = null;
  }

  getStatusBadgeClass(ativo: boolean): string {
    return ativo
      ? 'bg-green-500 text-white px-2 py-1 rounded-full text-xs'
      : 'bg-neutral-600 text-white px-2 py-1 rounded-full text-xs';
  }

  // Helper para acessar os controles do formulário no template
  get f() {
    return this.pratoForm.controls;
  }

  // Método para remover a imagem selecionada
  removerImagem(): void {
    this.imagemFile = null;
    this.imagemPreview = this.imagemAtual || null;
  }
}
