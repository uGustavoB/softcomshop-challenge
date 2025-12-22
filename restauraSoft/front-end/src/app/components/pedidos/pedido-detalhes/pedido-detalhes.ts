import {Component, EventEmitter, Input, OnInit, Output} from '@angular/core';
import {Pedido, PedidoItem, PedidosService} from '../../../services/pedidos/pedidos.service';
import {PratosService} from '../../../services/pratos/pratos.service';
import {ToastrService} from 'ngx-toastr';
import {MatIconModule} from '@angular/material/icon';
import {CommonModule} from '@angular/common';

interface StatusConfig {
  label: string;
  color: string;
  bgColor: string;
}

@Component({
  selector: 'app-pedido-detalhes',
  imports: [
    CommonModule,
    MatIconModule
  ],
  templateUrl: './pedido-detalhes.html',
  styleUrl: './pedido-detalhes.css',
})
export class PedidoDetalhes implements OnInit{
  @Input() pedidoId: number | null = null;
  @Output() close = new EventEmitter<void>();

  pedido: Pedido | null = null;
  pedidoItems: PedidoItem[] = [];
  pratosNomes: { [id: number]: string } = {};

  isLoading = false;

  statusConfig: Record<string, StatusConfig> = {
    pendente: { label: "Pendente", color: "text-amber-400", bgColor: "bg-amber-500/10" },
    em_preparo: { label: "Em Preparo", color: "text-blue-400", bgColor: "bg-blue-500/10" },
    pronto: { label: "Pronto", color: "text-purple-400", bgColor: "bg-purple-500/10" },
    entregue: { label: "Entregue", color: "text-green-400", bgColor: "bg-green-500/10" },
    cancelado: { label: "Cancelado", color: "text-red-400", bgColor: "bg-red-500/10" },
    finalizado: { label: "Finalizado", color: "text-neutral-400", bgColor: "bg-neutral-500/10" },
  };

  constructor(
    private pedidosService: PedidosService,
    private pratosService: PratosService,
    private toastr: ToastrService
  ) {}

  ngOnInit(): void {
    if (this.pedidoId) {
      this.loadPedidoDetails();
    }
  }

  loadPedidoDetails(): void {
    if (!this.pedidoId) return;

    this.isLoading = true;

    // Carrega dados do pedido
    this.pedidosService.getPedidoById(this.pedidoId).subscribe({
      next: (pedido) => {
        this.pedido = pedido;

        // Carrega itens do pedido
        this.pedidosService.getPedidoItems(this.pedidoId!).subscribe({
          next: (items) => {
            this.pedidoItems = items;
            this.loadPratosNomes(items);
            this.isLoading = false;
          },
          error: (error) => {
            console.error('Erro ao carregar itens do pedido:', error);
            this.toastr.error('Erro ao carregar itens do pedido', 'Erro');
            this.isLoading = false;
          }
        });
      },
      error: (error) => {
        console.error('Erro ao carregar pedido:', error);
        this.toastr.error('Erro ao carregar pedido', 'Erro');
        this.isLoading = false;
      }
    });
  }

  loadPratosNomes(items: PedidoItem[]): void {
    const pratoIds = [...new Set(items.map(item => item.prato_id))];

    pratoIds.forEach(pratoId => {
      this.pratosService.getPratoById(pratoId).subscribe({
        next: (prato) => {
          this.pratosNomes[pratoId] = prato.nome;
        },
        error: () => {
          this.pratosNomes[pratoId] = `Item #${pratoId}`;
        }
      });
    });
  }

  getStatusInfo(status: string): StatusConfig {
    return this.statusConfig[status] || this.statusConfig["pendente"];
  }

  formatCurrency(value: any): string {
    const num = typeof value === 'string' ? parseFloat(value) : value;
    return isNaN(num) ? '0.00' : num.toFixed(2);
  }

  closeModal(): void {
    this.close.emit();
  }
}
