import { Injectable } from '@angular/core';
import {ApiService} from '../API/api.service';
import {Observable} from 'rxjs';

export interface Prato {
  id: number;
  nome: string;
  descricao?: string;
  preco: number;
  imagem?: string;
  categoria_id: number;
  ativo: boolean;
}

export interface PratoRequest {
  nome: string;
  descricao?: string;
  preco: number;
  imagem?: string;
  categoria_id: number;
  ativo?: boolean;
}

@Injectable({
  providedIn: 'root',
})
export class PratosService {
  constructor(private api: ApiService) {}

  getPratos(): Observable<Prato[]> {
    return this.api.get<Prato[]>('prato');
  }

  getPratoById(id: number): Observable<Prato> {
    return this.api.get<Prato>(`prato/${id}`);
  }

  createPrato(prato: PratoRequest): Observable<Prato> {
    return this.api.post<Prato>('prato', prato);
  }

  updatePrato(id: number, prato: PratoRequest): Observable<Prato> {
    return this.api.put<Prato>(`prato/${id}`, prato);
  }

  deletePrato(id: number): Observable<any> {
    return this.api.delete<any>(`prato/${id}`);
  }

  // Método para upload de imagem (se necessário separado)
  uploadImagem(pratoId: number, imagem: File): Observable<any> {
    const formData = new FormData();
    formData.append('imagem', imagem);

    return this.api.post<any>(`/prato/${pratoId}/imagem`, formData);
  }
}
