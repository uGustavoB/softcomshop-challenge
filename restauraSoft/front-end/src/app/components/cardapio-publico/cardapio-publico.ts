import {Component, OnInit} from '@angular/core';
import {Categoria, CategoriasService} from '../../services/categorias/categorias.service';
import {Prato, PratosService} from '../../services/pratos/pratos.service';
import {CommonModule} from '@angular/common';
import {FormsModule} from '@angular/forms';
import {RouterModule} from '@angular/router';
import {MatIconModule} from '@angular/material/icon';

@Component({
  selector: 'app-cardapio-publico',
  standalone: true,
  imports: [CommonModule, FormsModule, RouterModule, MatIconModule],
  templateUrl: './cardapio-publico.html',
  styleUrls: ['./cardapio-publico.css']
})
export class CardapioPublicoComponent implements OnInit {
  // Estados
  categorias: Categoria[] = [];
  pratos: Prato[] = [];
  loading = true;
  searchTerm = '';
  selectedCategoria: number | null = null;
  selectedPrato: Prato | null = null;

  // Filtros
  filteredPratos: Prato[] = [];

  // Ano atual para o footer
  currentYear = new Date().getFullYear();

  constructor(
    private pratosService: PratosService,
    private categoriasService: CategoriasService
  ) {}

  ngOnInit(): void {
    this.loadData();
  }

  loadData(): void {
    // Carrega categorias e pratos em paralelo
    this.categoriasService.getCategorias().subscribe({
      next: (categorias) => {
        // Filtra apenas categorias ativas
        this.categorias = categorias.filter(c => c.ativo);

        // Carrega os pratos após categorias
        this.pratosService.getPratos().subscribe({
          next: (pratos) => {
            // Filtra apenas pratos ativos
            this.pratos = pratos.filter(p => p.ativo);
            this.applyFilters();

            // Simula loading (como no React)
            setTimeout(() => {
              this.loading = false;
            }, 800);
          },
          error: (error) => {
            console.error('Erro ao carregar pratos:', error);
            this.loading = false;
          }
        });
      },
      error: (error) => {
        console.error('Erro ao carregar categorias:', error);
        this.loading = false;
      }
    });
  }

  applyFilters(): void {
    this.filteredPratos = this.pratos.filter((prato) => {
      // Filtro por busca
      const matchesSearch = this.searchTerm === '' ||
        prato.nome.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
        (prato.descricao && prato.descricao.toLowerCase().includes(this.searchTerm.toLowerCase()));

      // Filtro por categoria
      const matchesCategory = this.selectedCategoria === null ||
        prato.categoria_id === this.selectedCategoria;

      return matchesSearch && matchesCategory;
    });
  }

  getPratosByCategoria(categoriaId: number): Prato[] {
    return this.filteredPratos.filter(p => p.categoria_id === categoriaId);
  }

  getCategoriaById(categoriaId: number): Categoria | undefined {
    return this.categorias.find(c => c.id === categoriaId);
  }

  getCategoriaName(categoriaId: number): string {
    const categoria = this.categorias.find(c => c.id === categoriaId);
    return categoria?.nome || 'Categoria';
  }

  onSearchChange(event: Event): void {
    const input = event.target as HTMLInputElement;
    this.searchTerm = input.value;
    this.applyFilters();
  }

  clearSearch(): void {
    this.searchTerm = '';
    this.applyFilters();
  }

  selectCategoria(categoriaId: number | null): void {
    this.selectedCategoria = categoriaId;
    this.applyFilters();
  }

  openPratoModal(prato: Prato): void {
    this.selectedPrato = prato;
  }

  closePratoModal(): void {
    this.selectedPrato = null;
  }

  getPratoImage(prato: Prato): string {
    // Usa imagem_url se disponível, senão usa imagem normal
    const imageUrl = prato.imagem_url || prato.imagem;

    if (imageUrl) {
      return imageUrl;
    }

    // Placeholder
    return 'assets/images/placeholder.svg';
  }

  // Verifica se há pratos na categoria
  hasPratosInCategory(categoriaId: number): boolean {
    return this.getPratosByCategoria(categoriaId).length > 0;
  }

  // Verifica se há resultados
  hasResults(): boolean {
    return this.filteredPratos.length > 0;
  }

  // Obtém cor da badge baseado no status
  getStatusBadgeClass(ativo: boolean): string {
    return ativo
      ? 'bg-green-500 text-white px-2 py-1 rounded-full text-xs'
      : 'bg-neutral-600 text-white px-2 py-1 rounded-full text-xs';
  }
}
