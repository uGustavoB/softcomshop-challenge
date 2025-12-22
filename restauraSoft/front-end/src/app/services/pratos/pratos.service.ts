import { Injectable } from '@angular/core';
import {ApiService} from '../API/api.service';
import {Observable} from 'rxjs';

export interface Prato {
  id: number;
  nome: string;
  descricao?: string;
  preco: number;
  imagem?: string;
  imagem_url?: string;
  categoria_id: number;
  ativo: boolean;
}

export interface PratoRequest {
  nome: string;
  descricao?: string;
  preco: number;
  imagem?: string | File;
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

  createPrato(pratoData: PratoRequest, imagemFile?: File | null): Observable<any> {
    // Se tiver imagem, cria FormData
    if (imagemFile) {
      const formData = new FormData();
      formData.append('nome', pratoData.nome);
      formData.append('descricao', pratoData.descricao || '');
      formData.append('preco', pratoData.preco.toString());
      formData.append('categoria_id', pratoData.categoria_id.toString());

      const ativoValue = pratoData.ativo !== undefined ? pratoData.ativo : true;
      formData.append('ativo', ativoValue ? '1' : '0');

      formData.append('imagem', imagemFile);

      return this.api.postFormData('prato', formData);
    } else {
      // Sem imagem, envia como JSON normal
      return this.api.post<Prato>('prato', pratoData);
    }
  }

  updatePrato(id: number, pratoData: PratoRequest, imagemFile?: File | null) {
    if (imagemFile) {
      const formData = new FormData();

      formData.append('_method', 'PUT'); // 🔥 ESSENCIAL
      formData.append('nome', pratoData.nome);
      formData.append('descricao', pratoData.descricao || '');
      formData.append('preco', pratoData.preco.toString());
      formData.append('categoria_id', pratoData.categoria_id.toString());

      const ativoValue = pratoData.ativo !== undefined ? pratoData.ativo : true;
      formData.append('ativo', ativoValue ? '1' : '0');

      formData.append('imagem', imagemFile);

      return this.api.postFormData(`prato/${id}`, formData);
    }

    return this.api.put(`prato/${id}`, pratoData);
  }

  deletePrato(id: number): Observable<any> {
    return this.api.delete<any>(`prato/${id}`);
  }
}
