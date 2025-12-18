import { Component } from '@angular/core';
import {CommonModule} from '@angular/common';
import {FormsModule} from '@angular/forms';
import {MatIconModule} from '@angular/material/icon';
import {Mesa, MesasService} from '../../services/mesas/mesas.service';
import {ToastrService} from 'ngx-toastr';
import {MesaSalvar} from './mesa-salvar/mesa-salvar';

interface StatusConfig {
  label: string;
  color: string;
  bgColor: string;
  borderColor: string;
}

@Component({
  selector: 'app-mesas',
  imports: [
    CommonModule,
    FormsModule,
    MatIconModule,
    MesaSalvar
  ],
  standalone: true,
  templateUrl: './mesas.html',
  styleUrl: './mesas.css',
})
export class Mesas {
  mesas: Mesa[] = [];
  isLoading = false;

  // Estados do modal
  selectedMesa: Mesa | null = null;
  isModalOpen = false;

  // Configuração de status (ajustada para os status do seu service)
  statusConfig: Record<string, StatusConfig> = {
    livre: {
      label: 'Disponível',
      color: 'text-green-400',
      bgColor: 'bg-green-500/10',
      borderColor: 'border-green-900'
    },
    ocupada: {
      label: 'Ocupada',
      color: 'text-red-400',
      bgColor: 'bg-red-500/10',
      borderColor: 'border-red-900'
    },
    reservada: {
      label: 'Reservada',
      color: 'text-amber-400',
      bgColor: 'bg-amber-500/10',
      borderColor: 'border-amber-900'
    },
    manutencao: {
      label: 'Manutenção',
      color: 'text-neutral-500',
      bgColor: 'bg-neutral-800/50',
      borderColor: 'border-neutral-700'
    }
  };

  constructor(
    private mesasService: MesasService,
    private toastr: ToastrService
  ) {}

  ngOnInit(): void {
    this.loadMesas();
  }

  loadMesas(): void {
    this.isLoading = true;
    this.mesasService.getMesas().subscribe({
      next: (mesas) => {
        this.mesas = mesas;
        this.isLoading = false;
      },
      error: (error) => {
        console.error('Erro ao carregar mesas:', error);
        this.toastr.error('Erro ao carregar mesas', 'Erro');
        this.isLoading = false;
      }
    });
  }

  handleMesaClick(mesa: Mesa): void {
    this.selectedMesa = mesa;
    this.isModalOpen = true;
  }

  handleNovaMesa(): void {
    this.selectedMesa = null;
    this.isModalOpen = true;
  }

  handleCloseModal(): void {
    this.isModalOpen = false;
    this.selectedMesa = null;
    this.loadMesas(); // Refresh tables data
  }

  getStatusInfo(status: string): StatusConfig {
    return this.statusConfig[status] || this.statusConfig["livre"];
  }

  getStatusLabel(status: string): string {
    const config = this.getStatusInfo(status);
    return config.label;
  }
}
