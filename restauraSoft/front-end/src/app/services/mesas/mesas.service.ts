import { Injectable } from '@angular/core';
import {ApiService} from '../API/api.service';
import {Observable} from 'rxjs';

export interface Mesa {
  id: number;
  numero: string;
  capacidade: number;
  descricao?: string;
  status: 'livre' | 'ocupada' | 'reservada' | 'manutencao';
  localizacao?: string;
}

export interface MesaRequest {
  numero: string;
  capacidade: number;
  descricao?: string;
  status: 'livre' | 'ocupada' | 'reservada' | 'manutencao';
  localizacao?: string;
}

@Injectable({
  providedIn: 'root',
})
export class MesasService {
  constructor(private api: ApiService) {}

  getMesas(): Observable<Mesa[]> {
    return this.api.get<Mesa[]>('mesa');
  }

  getMesaById(id: number): Observable<Mesa> {
    return this.api.get<Mesa>(`mesa/${id}`);
  }

  createMesa(mesa: MesaRequest): Observable<Mesa> {
    return this.api.post<Mesa>('mesa', mesa);
  }

  updateMesa(id: number, mesa: MesaRequest): Observable<Mesa> {
    return this.api.put<Mesa>(`mesa/${id}`, mesa);
  }

  deleteMesa(id: number): Observable<any> {
    return this.api.delete<any>(`mesa/${id}`);
  }
}
