import {Component, OnInit, ViewChild} from '@angular/core';
import {Categoria, CategoriaRequest, CategoriasService} from '../../services/categorias/categorias.service';
import {MatTableDataSource} from '@angular/material/table';
import {MatPaginator} from '@angular/material/paginator';
import {MatSort} from '@angular/material/sort';
import {ToastrService} from 'ngx-toastr';
import {MatIconModule} from '@angular/material/icon';
import {CommonModule} from '@angular/common';
import {FormsModule} from '@angular/forms';
import {fadeSlide} from '../../animations/FadeSlide';
import {itemAnimation} from '../../animations/ItemAnimation';

@Component({
  selector: 'app-categorias',
  imports: [
    MatIconModule,
    CommonModule,
    FormsModule
  ],
  templateUrl: './categorias.html',
  styleUrl: './categorias.css',
  animations: [fadeSlide, itemAnimation]
})
export class Categorias implements OnInit{
  categorias: Categoria[] = [];
  dataSource = new MatTableDataSource<Categoria>();

  // Estados do modal
  isEditModalOpen = false;
  isDeleteModalOpen = false;
  selectedCategoria: Categoria | null = null;
  isLoading = false;

  // Estados iniciais do form
  nome = '';
  descricao = '';
  ordem = 0;
  ativo = true;

  @ViewChild(MatPaginator) paginator!: MatPaginator;
  @ViewChild(MatSort) sort!: MatSort;

  constructor(
    private categoriaService: CategoriasService,
    private toastr: ToastrService
  ) {}

  ngOnInit(): void {
    this.loadCategorias();
  }

  loadCategorias(): void {
    this.isLoading = true;
    this.categoriaService.getCategorias().subscribe({
      next: (categorias) => {
        this.categorias = categorias.sort(
          (a, b) => (a.ordem ?? 0) - (b.ordem ?? 0)
        );
        this.dataSource.data = categorias;
        this.isLoading = false;
      },
      error: (error) => {
        console.error('Erro ao carregar categorias:', error);
        this.toastr.error('Erro ao carregar categorias', 'Erro');
        this.isLoading = false;
      }
    });
  }

  handleNovaCategoria(): void {
    this.selectedCategoria = null;
    this.nome = '';
    this.descricao = '';
    this.ordem = 0;
    this.ativo = true;
    this.isEditModalOpen = true;
  }

  handleEditCategoria(categoria: Categoria): void {
    this.selectedCategoria = categoria;
    this.nome = categoria.nome;
    this.descricao = categoria.descricao || '';
    this.ordem = categoria.ordem || 0;
    this.ativo = categoria.ativo;
    this.isEditModalOpen = true;
  }

  handleDeleteCategoria(categoria: Categoria): void {
    this.selectedCategoria = categoria;
    this.isDeleteModalOpen = true;
  }

  handleSaveCategoria(): void {
    if (!this.nome.trim()) {
      this.toastr.warning('Nome é obrigatório', 'Atenção');
      return;
    }

    this.isLoading = true;

    const categoriaData: CategoriaRequest = {
      nome: this.nome,
      descricao: this.descricao || undefined,
      ordem: this.ordem || 0,
      ativo: this.ativo
    };

    const request = this.selectedCategoria
      ? this.categoriaService.updateCategoria(this.selectedCategoria.id, categoriaData)
      : this.categoriaService.createCategoria(categoriaData);

    request.subscribe({
      next: (response) => {
        this.toastr.success(
          this.selectedCategoria
            ? 'Categoria atualizada com sucesso!'
            : 'Categoria criada com sucesso!',
          'Sucesso'
        );
        this.loadCategorias();
        this.isEditModalOpen = false;
        this.isLoading = false;
      },
      error: (error) => {
        console.error('Erro ao salvar categoria:', error);

        if (error.status === 409) {
          this.toastr.error('Já existe uma categoria com este nome', 'Erro');
        } else {
          this.toastr.error('Erro ao salvar categoria', 'Erro');
        }

        this.isLoading = false;
      }
    });
  }

  handleConfirmDelete(): void {
    if (!this.selectedCategoria) return;

    this.isLoading = true;
    this.categoriaService.deleteCategoria(this.selectedCategoria.id).subscribe({
      next: () => {
        this.toastr.success('Categoria excluída com sucesso!', 'Sucesso');
        this.loadCategorias();
        this.isDeleteModalOpen = false;
        this.isLoading = false;
      },
      error: (error) => {
        console.error('Erro ao excluir categoria:', error);
        this.toastr.error('Erro ao excluir categoria', 'Erro');
        this.isLoading = false;
      }
    });
  }

  closeEditModal(): void {
    this.isEditModalOpen = false;
    this.selectedCategoria = null;
    this.resetForm();
  }

  closeDeleteModal(): void {
    this.isDeleteModalOpen = false;
    this.selectedCategoria = null;
  }

  resetForm(): void {
    this.nome = '';
    this.descricao = '';
    this.ordem = 0;
    this.ativo = true;
  }

  getStatusBadgeClass(ativo: boolean): string {
    return ativo
      ? 'bg-green-500 text-white px-2 py-1 rounded-full text-xs'
      : 'bg-neutral-600 text-white px-2 py-1 rounded-full text-xs';
  }
}
