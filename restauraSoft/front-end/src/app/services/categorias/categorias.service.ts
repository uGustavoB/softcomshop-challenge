import { Injectable } from '@angular/core';
import {ApiService} from '../API/api.service';
import {Observable} from 'rxjs';

export interface Categoria {
  id: number;
  nome: string;
  descricao?: string;
  ordem: number;
  ativo: boolean;
}

export interface CategoriaRequest {
  nome: string;
  descricao?: string;
  ordem?: number;
  ativo?: boolean;
}

@Injectable({
  providedIn: 'root',
})
export class CategoriasService {
  constructor(private api: ApiService) {}

  getCategorias(): Observable<Categoria[]> {
    return this.api.get<Categoria[]>('categoria');
  }

  getCategoriaById(id: number): Observable<Categoria> {
    return this.api.get<Categoria>(`categoria/${id}`);
  }

  createCategoria(categoria: CategoriaRequest): Observable<Categoria> {
    return this.api.post<Categoria>('categoria', categoria);
  }

  updateCategoria(id: number, categoria: CategoriaRequest): Observable<Categoria> {
    return this.api.put<Categoria>(`categoria/${id}`, categoria);
  }

  deleteCategoria(id: number): Observable<any> {
    return this.api.delete<any>(`categoria/${id}`);
  }
}
