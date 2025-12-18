import {Component, OnInit} from '@angular/core';
import {Prato, PratoRequest, PratosService} from '../../services/pratos/pratos.service';
import {Categoria, CategoriasService} from '../../services/categorias/categorias.service';
import {FormBuilder, FormGroup, FormsModule, ReactiveFormsModule, Validators} from '@angular/forms';
import {ToastrService} from 'ngx-toastr';
import {MatIconModule} from '@angular/material/icon';
import {CommonModule} from '@angular/common';
import {fadeSlide} from '../../animations/FadeSlide';
import {itemAnimation} from '../../animations/ItemAnimation';

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
  isUploading = false;

  // Formulário reativo
  pratoForm!: FormGroup;
  imagemPreview: string | ArrayBuffer | null = null;
  imagemFile: File | null = null;

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
      ativo: [true],
      imagem: ['']
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
        this.categorias = categorias.filter(c => c.ativo); // Filtra apenas categorias ativas
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
      ativo: true,
      imagem: ''
    });
    this.imagemPreview = null;
    this.imagemFile = null;
    this.isEditModalOpen = true;
  }

  handleEditPrato(prato: Prato): void {
    this.selectedPrato = prato;
    this.pratoForm.patchValue({
      nome: prato.nome,
      descricao: prato.descricao || '',
      preco: prato.preco,
      categoria_id: prato.categoria_id,
      ativo: prato.ativo,
      imagem: prato.imagem || ''
    });
    this.imagemPreview = prato.imagem || null;
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
      descricao: formValue.descricao || undefined,
      preco: formValue.preco,
      categoria_id: formValue.categoria_id,
      ativo: formValue.ativo,
      imagem: formValue.imagem || undefined
    };

    // Se houver nova imagem, vamos fazer upload separado após salvar o prato
    const request = this.selectedPrato
      ? this.pratoService.updatePrato(this.selectedPrato.id, pratoData)
      : this.pratoService.createPrato(pratoData);

    request.subscribe({
      next: (response) => {
        // Se houver imagem para upload, fazemos após criar/editar o prato
        if (this.imagemFile && response.id) {
          this.uploadImagem(response.id);
        } else {
          this.toastr.success(
            this.selectedPrato
              ? 'Prato atualizado com sucesso!'
              : 'Prato criado com sucesso!',
            'Sucesso'
          );
          this.loadPratos();
          this.isEditModalOpen = false;
          this.isLoading = false;
        }
      },
      error: (error) => {
        console.error('Erro ao salvar prato:', error);

        if (error.status === 409) {
          this.toastr.error('Já existe um prato com este nome', 'Erro');
        } else if (error.status === 400) {
          this.toastr.error('Categoria inválida', 'Erro');
        } else {
          this.toastr.error('Erro ao salvar prato', 'Erro');
        }

        this.isLoading = false;
      }
    });
  }

  uploadImagem(pratoId: number): void {
    if (!this.imagemFile) return;

    this.isUploading = true;
    this.pratoService.uploadImagem(pratoId, this.imagemFile).subscribe({
      next: () => {
        this.toastr.success('Imagem salva com sucesso!', 'Sucesso');
        this.loadPratos();
        this.isEditModalOpen = false;
        this.isLoading = false;
        this.isUploading = false;
      },
      error: (error) => {
        console.error('Erro ao fazer upload da imagem:', error);
        this.toastr.error('Erro ao fazer upload da imagem', 'Erro');
        this.isLoading = false;
        this.isUploading = false;
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
    this.pratoForm.reset({
      nome: '',
      descricao: '',
      preco: 0,
      categoria_id: '',
      ativo: true,
      imagem: ''
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
}
