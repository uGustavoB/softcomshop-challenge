import { HttpClient, HttpParams } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Router } from '@angular/router';
import { ToastrService } from 'ngx-toastr';
import { Observable, throwError } from 'rxjs';
import { tap, catchError, map } from 'rxjs/operators';

export interface ApiResponse<T> {
  message: string;
  type: string;
  email?: string;
  token?: string;
  data?: T;
  dataList?: T[];
  pagination?: {
    currentPage: number;
    pageSize: number;
    totalPages: number;
    totalElements: number;
  };
}

@Injectable({
  providedIn: 'root'
})
export class ApiService {
  constructor(private http: HttpClient, private router: Router, private toast: ToastrService) {}

  private handleError(error: any): Observable<never> {
    if (error.status === 401) {
      this.toast.error('Sessão expirada. Por favor, faça login novamente.', 'Erro de Autenticação');
      localStorage.removeItem('token');
      this.router.navigate(['/auth']);
    } else if (error.status === 403) {
      this.toast.error('Acesso negado. Você não tem permissão para realizar esta ação.', 'Erro de Permissão');
    } else if (error.status === 409) {
      console.log("Erro já tratado no componente");
    } else if (error.status === 422) {
      for (const validationError of error.error.dataList) {
        this.toast.error(validationError.description, 'Erro de Validação');
      }
    } else if (error.status === 500) {
      this.toast.error('Erro interno do servidor. Tente novamente mais tarde.', 'Erro de Servidor');
    } else {
      this.toast.error('Ocorreu um erro inesperado', 'Erro Inesperado');
      console.error('Erro inesperado:', error);
    }
    return throwError(() => error);
  }

  get<T>(url: string, params?: HttpParams): Observable<{ data: T; pagination?: ApiResponse<any>['pagination'] }> {
    return this.http.get<ApiResponse<any>>(url, { params }).pipe(
      tap(response => {
        if (response.token) {
          localStorage.setItem('token', response.token);
        }
        console.log(response.type === 'Success'
          ? `GET: ${response.message}`
          : `GET failed: ${response.message}`);
      }),
      map(response => ({
        data: response.dataList ?? response.data,
        pagination: response.pagination
      })),
      catchError(error => this.handleError(error))
    );
  }


  post<T>(url: string, body: any): Observable<T> {
    return this.http.post<ApiResponse<T>>(url, body).pipe(
      tap(response => {
        if (response.token) {
          localStorage.setItem('token', response.token);
        }
        console.log(response.type === 'Success'
          ? `POST: ${response.message}`
          : `POST failed: ${response.message}`);
      }),
      map(response => response.data as T),
      catchError(error => this.handleError(error))
    );
  }

  put<T>(url: string, body: any): Observable<T> {
    return this.http.put<ApiResponse<T>>(url, body).pipe(
      tap(response => {
        console.log(response.type === 'Success'
          ? `PUT: ${response.message}`
          : `PUT failed: ${response.message}`);
      }),
      map(response => response.data as T),
      catchError(error => this.handleError(error))
    );
  }

  delete<T>(url: string): Observable<T> {
    return this.http.delete<ApiResponse<T>>(url).pipe(
      tap(response => {
        console.log(response.type === 'Success'
          ? `DELETE: ${response.message}`
          : `DELETE failed: ${response.message}`);
      }),
      map(response => response.data as T),
      catchError(error => this.handleError(error))
    );
  }
}
