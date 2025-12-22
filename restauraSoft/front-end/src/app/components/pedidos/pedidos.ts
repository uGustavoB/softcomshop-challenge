import {Component, OnInit} from '@angular/core';
import {CommonModule} from '@angular/common';
import {MatIconModule} from '@angular/material/icon';
import {Pedido, PedidosService} from '../../services/pedidos/pedidos.service';
import {ToastrService} from 'ngx-toastr';
import {PedidoDetalhes} from './pedido-detalhes/pedido-detalhes';
import {PedidoEditar} from './pedido-editar/pedido-editar';
import {itemAnimation} from '../../animations/ItemAnimation';

interface StatusConfig {
  label: string;
  variant: string;
  icon: string;
  color: string;
  bgColor: string;
}
@Component({
  selector: 'app-pedidos',
  imports: [
    CommonModule,
    MatIconModule,
    PedidoDetalhes,
    PedidoEditar
  ],
  templateUrl: './pedidos.html',
  styleUrl: './pedidos.css',
  animations: [itemAnimation]
})
export class Pedidos implements OnInit{
  pedidos: Pedido[] = [];
  isLoading = false;

  // Estados dos modais
  selectedPedidoId: number | null = null;
  isModalOpen = false;
  isEditModalOpen = false;
  isDeleteModalOpen = false;
  pedidoToDelete: number | null = null;

  // Configuração de status
  statusConfig: Record<string, StatusConfig> = {
    pendente: {
      label: "Pendente",
      variant: "outline",
      icon: "schedule",
      color: "text-amber-400",
      bgColor: "bg-amber-500/10"
    },
    em_preparo: {
      label: "Em Preparo",
      variant: "default",
      icon: "chef_hat",
      color: "text-blue-400",
      bgColor: "bg-blue-500/10"
    },
    pronto: {
      label: "Pronto",
      variant: "secondary",
      icon: "check_circle",
      color: "text-purple-400",
      bgColor: "bg-purple-500/10"
    },
    entregue: {
      label: "Entregue",
      variant: "secondary",
      icon: "check_circle",
      color: "text-green-400",
      bgColor: "bg-green-500/10"
    },
    cancelado: {
      label: "Cancelado",
      variant: "destructive",
      icon: "cancel",
      color: "text-red-400",
      bgColor: "bg-red-500/10"
    },
    finalizado: {
      label: "Finalizado",
      variant: "secondary",
      icon: "check_circle",
      color: "text-neutral-400",
      bgColor: "bg-neutral-500/10"
    }
  };

  constructor(
    private pedidosService: PedidosService,
    private toastr: ToastrService
  ) {}

  ngOnInit(): void {
    this.loadPedidos();
  }

  loadPedidos(): void {
    this.isLoading = true;
    this.pedidosService.getPedidos().subscribe({
      next: (pedidos) => {
        this.pedidos = pedidos;
        this.isLoading = false;
      },
      error: (error) => {
        console.error('Erro ao carregar pedidos:', error);
        this.toastr.error('Erro ao carregar pedidos', 'Erro');
        this.isLoading = false;
      }
    });
  }

  handleVerDetalhes(pedidoId: number): void {
    this.selectedPedidoId = pedidoId;
    this.isModalOpen = true;
  }

  handleEdit(pedidoId: number): void {
    this.selectedPedidoId = pedidoId;
    this.isEditModalOpen = true;
  }

  handleDelete(pedidoId: number): void {
    this.pedidoToDelete = pedidoId;
    this.isDeleteModalOpen = true;
  }

  confirmDelete(): void {
    if (!this.pedidoToDelete) return;

    this.isLoading = true;
    this.pedidosService.deletePedido(this.pedidoToDelete).subscribe({
      next: () => {
        this.toastr.success('Pedido excluído com sucesso!', 'Sucesso');
        this.loadPedidos();
        this.closeDeleteModal();
        this.isLoading = false;
      },
      error: (error) => {
        console.error('Erro ao excluir pedido:', error);
        this.toastr.error(error.error?.message || 'Erro ao excluir pedido', 'Erro');
        this.isLoading = false;
      }
    });
  }

  closeModal(): void {
    this.isModalOpen = false;
    this.selectedPedidoId = null;
  }

  closeEditModal(): void {
    this.isEditModalOpen = false;
    this.selectedPedidoId = null;
    this.loadPedidos(); // Recarrega a lista após edição
  }

  closeDeleteModal(): void {
    this.isDeleteModalOpen = false;
    this.pedidoToDelete = null;
  }

  getStatusInfo(status: string): StatusConfig {
    return this.statusConfig[status] || this.statusConfig["pendente"];
  }

  formatCurrency(value: any): string {
    const num = typeof value === 'string' ? parseFloat(value) : value;
    return isNaN(num) ? '0.00' : num.toFixed(2);
  }

  formatPaymentMethod(method: string | null | undefined): string {
    // Verifica se o método é válido
    if (!method) {
      return 'Não informado';
    }

    // Faz as transformações
    return method
      .replace('_', ' ') // Substitui underscores por espaços
      .replace(/\b\w/g, l => l.toUpperCase()); // Capitaliza cada palavra
  }
}
