import {Component, EventEmitter, Output, Input, OnInit} from '@angular/core';
import {CommonModule} from '@angular/common';
import {FormsModule} from '@angular/forms';
import {MatIconModule} from '@angular/material/icon';
import {Mesa, MesaRequest, MesasService} from '../../../services/mesas/mesas.service';
import {ToastrService} from 'ngx-toastr';

@Component({
  selector: 'app-mesa-salvar',
  imports: [
    CommonModule,
    FormsModule,
    MatIconModule
  ],
  standalone: true,
  templateUrl: './mesa-salvar.html',
  styleUrl: './mesa-salvar.css',
})
export class MesaSalvar implements OnInit{
  @Input() mesa: Mesa | null = null;
  @Output() close = new EventEmitter<void>();

  isLoading = false;
  isEditMode = false;

  // Form fields
  numero: string = '';
  capacidade: number = 1;
  status: 'livre' | 'ocupada' | 'reservada' | 'manutencao' = 'livre';
  localizacao: string = '';
  ativo: boolean = true;

  statusOptions = [
    { value: 'livre', label: 'Disponível' },
    { value: 'ocupada', label: 'Ocupada' },
    { value: 'reservada', label: 'Reservada' },
    { value: 'manutencao', label: 'Manutenção' }
  ];

  constructor(
    private mesasService: MesasService,
    private toastr: ToastrService
  ) {}

  ngOnInit(): void {
    this.isEditMode = !!this.mesa;

    if (this.mesa) {
      this.numero = this.mesa.numero;
      this.capacidade = this.mesa.capacidade;
      this.status = this.mesa.status;
      this.localizacao = this.mesa.localizacao || '';
    } else {
      // Valores padrão para nova mesa
      this.numero = this.getNextTableNumber().toString();
      this.capacidade = 4;
      this.status = 'livre';
      this.ativo = true;
    }
  }

  getNextTableNumber(): number {
    // Em uma implementação real, você buscaria o próximo número disponível
    // Por enquanto, vamos usar um valor fixo ou calcular baseado nas mesas existentes
    return 1;
  }

  handleSave(): void {
    if (!this.numero || this.numero.trim() === '') {
      this.toastr.warning('Número da mesa é obrigatório', 'Atenção');
      return;
    }

    if (!this.capacidade || this.capacidade <= 0) {
      this.toastr.warning('Capacidade deve ser maior que zero', 'Atenção');
      return;
    }

    this.isLoading = true;

    const mesaData: MesaRequest = {
      numero: this.numero,
      capacidade: this.capacidade,
      status: this.status,
      localizacao: this.localizacao
    };

    const request = this.isEditMode && this.mesa
      ? this.mesasService.updateMesa(this.mesa.id, mesaData)
      : this.mesasService.createMesa(mesaData);

    request.subscribe({
      next: () => {
        this.toastr.success(
          this.isEditMode
            ? 'Mesa atualizada com sucesso!'
            : 'Mesa criada com sucesso!',
          'Sucesso'
        );
        this.closeModal();
      },
      error: (error) => {
        console.error('Erro ao salvar mesa:', error);

        if (error.status === 409) {
          this.toastr.error('Já existe uma mesa com este número', 'Erro');
        } else {
          this.toastr.error('Erro ao salvar mesa', 'Erro');
        }

        this.isLoading = false;
      }
    });
  }

  handleDelete(): void {
    if (!this.mesa) return;

    if (!confirm('Tem certeza que deseja excluir esta mesa? Esta ação não pode ser desfeita.')) {
      return;
    }

    this.isLoading = true;
    this.mesasService.deleteMesa(this.mesa.id).subscribe({
      next: () => {
        this.toastr.success('Mesa excluída com sucesso!', 'Sucesso');
        this.closeModal();
      },
      error: (error) => {
        console.error('Erro ao excluir mesa:', error);
        this.toastr.error('Erro ao excluir mesa', 'Erro');
        this.isLoading = false;
      }
    });
  }

  closeModal(): void {
    this.close.emit();
  }
}
