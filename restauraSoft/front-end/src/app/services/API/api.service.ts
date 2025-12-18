import { HttpClient, HttpParams } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Router } from '@angular/router';
import { ToastrService } from 'ngx-toastr';
import { Observable, throwError } from 'rxjs';
import { tap, catchError, map } from 'rxjs/operators';
import {environment} from '../../../environments/environment';

export interface ApiResponse<T> {
  status: string;
  message: string;
  user?: {
    id: number;
    name: string;
    email: string;
  }
  type: string;
  authorization?: {
    token: string
  };
  data?: T;
}

@Injectable({
  providedIn: 'root'
})
export class ApiService {
  private apiUrl = `${environment.apiUrl}`;
  constructor(private http: HttpClient, private router: Router, private toast: ToastrService) {}

  private handleError(error: any): Observable<never> {
    if (error.status === 401) {
      this.toast.error('Sessão expirada. Por favor, faça login novamente.', 'Erro de Autenticação');
      localStorage.removeItem('token');
      this.router.navigate(['/login']);
    } else if (error.status === 403) {
      this.toast.error('Acesso negado. Você não tem permissão para realizar esta ação.', 'Erro de Permissão');
    } else if (error.status === 409) {
      console.log("Erro já tratado no componente");
    } else if (error.status === 422) {
      // Ajustado para a nova estrutura de erro
      if (error.error.data && Array.isArray(error.error.data)) {
        for (const validationError of error.error.data) {
          this.toast.error(validationError.description || validationError.message, 'Erro de Validação');
        }
      } else if (error.error.message) {
        this.toast.error(error.error.message, 'Erro de Validação');
      }
    } else if (error.status === 500) {
      this.toast.error('Erro interno do servidor. Tente novamente mais tarde.', 'Erro de Servidor');
    } else {
      this.toast.error('Ocorreu um erro inesperado', 'Erro Inesperado');
      console.error('Erro inesperado:', error);
    }
    return throwError(() => error);
  }

  get<T>(url: string, params?: HttpParams): Observable<T> {
    return this.http.get<ApiResponse<T>>(`${this.apiUrl}/${url}`, { params }).pipe(
      tap(response => {
        if (response.authorization?.token) {
          localStorage.setItem('token', response.authorization.token);
        }
        console.log(response.status === 'success' || response.status === 'Success'
          ? `GET: ${response.message}`
          : `GET failed: ${response.message}`);
      }),
      map(response => response.data as T),
      catchError(error => this.handleError(error))
    );
  }

  post<T>(url: string, body: any): Observable<T> {
    console.log(url, body);
    return this.http.post<ApiResponse<T>>(`${this.apiUrl}/${url}`, body).pipe(
      tap(response => {
        if (response.authorization?.token) {
          localStorage.setItem('token', response.authorization.token);
        }
        if (response.user) {
          localStorage.setItem('user', JSON.stringify(response.user));
        }
        console.log(response.status === 'success' || response.status === 'Success'
          ? `POST: ${response.message}`
          : `POST failed: ${response.message}`);
      }),
      map(response => response.data as T),
      catchError(error => this.handleError(error))
    );
  }

  put<T>(url: string, body: any): Observable<T> {
    return this.http.put<ApiResponse<T>>(`${this.apiUrl}/${url}`, body).pipe(
      tap(response => {
        if (response.authorization?.token) {
          localStorage.setItem('token', response.authorization.token);
        }
        console.log(response.status === 'success' || response.status === 'Success'
          ? `PUT: ${response.message}`
          : `PUT failed: ${response.message}`);
      }),
      map(response => response.data as T),
      catchError(error => this.handleError(error))
    );
  }

  delete<T>(url: string): Observable<T> {
    return this.http.delete<ApiResponse<T>>(`${this.apiUrl}/${url}`).pipe(
      tap(response => {
        if (response.authorization?.token) {
          localStorage.setItem('token', response.authorization.token);
        }
        console.log(response.status === 'success' || response.status === 'Success'
          ? `DELETE: ${response.message}`
          : `DELETE failed: ${response.message}`);
      }),
      map(response => response.data as T),
      catchError(error => this.handleError(error))
    );
  }

  // Método para fazer login que retorna o token de autorização
  // login<T>(url: string, credentials: any): Observable<{ data: T; token?: string }> {
  //   return this.http.post<ApiResponse<T>>(`${this.apiUrl}/${url}`, credentials).pipe(
  //     tap(response => {
  //       if (response.authorization?.token) {
  //         localStorage.setItem('token', response.authorization.token);
  //       }
  //       console.log(response.status === 'success' || response.status === 'Success'
  //         ? `LOGIN: ${response.message}`
  //         : `LOGIN failed: ${response.message}`);
  //     }),
  //     map(response => ({
  //       data: response.data as T,
  //       token: response.authorization?.token
  //     })),
  //     catchError(error => this.handleError(error))
  //   );
  // }
}
