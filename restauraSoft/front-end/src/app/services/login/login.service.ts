import {Injectable} from '@angular/core';
import {BehaviorSubject, catchError, Observable, tap, throwError} from 'rxjs';
import {ApiResponse, ApiService} from '../API/api.service';
import {Router} from '@angular/router';

interface User {
  id: number;
  name: string;
  email: string;
}

@Injectable({
  providedIn: 'root'
})
export class LoginService {
  private userSubject = new BehaviorSubject<User | null>(null);
  user$ = this.userSubject.asObservable();

  constructor(
    private api: ApiService,
    private router: Router
  ) {
    this.loadUserFromStorage();
  }

  login(email: string, password: string): Observable<ApiResponse<any>> {
    return this.api.authPost<any>('login', { email, password }).pipe(
      tap(response => {
        console.log("LoginService - Response completo:", response);
        this.handleAuthSuccess(response);
      }),
      catchError(error => {
        console.error('Login failed:', error);
        return throwError(() => error);
      })
    );
  }

  register(name: string, email: string, password: string): Observable<ApiResponse<any>> {
    return this.api.authPost<any>('register', { name, email, password }).pipe(
      tap(response => {
        console.log("LoginService - Registro response:", response);
        this.handleAuthSuccess(response);
      }),
      catchError(error => {
        console.error('Registration failed:', error);
        return throwError(() => error);
      })
    );
  }

  private handleAuthSuccess(response: ApiResponse<any>): void {
    console.log('LoginService - handleAuthSuccess:', response);

    if (response.user) {
      console.log('LoginService - Salvando usuário:', response.user);
      // Salva usuário no estado
      this.userSubject.next(response.user);

      // Salva no localStorage
      localStorage.setItem('user', JSON.stringify(response.user));
      console.log('LoginService - Usuário salvo no localStorage');

      // Salva token se existir
      if (response.authorization?.token) {
        localStorage.setItem('token', response.authorization.token);
        console.log('LoginService - Token salvo no localStorage:', response.authorization.token);
      }
    } else {
      console.warn('LoginService - Resposta sem usuário:', response);
    }
  }

  logout(): void {
    console.log('LoginService - Logout');

    // Limpa localStorage
    localStorage.removeItem('user');
    localStorage.removeItem('token');

    // Limpa estado - EMITE null!
    this.userSubject.next(null);

    // Redireciona para login
    this.router.navigate(['/login']);
  }

  get currentUser(): User | null {
    return this.userSubject.value;
  }

  // Carregar usuário do localStorage
  private loadUserFromStorage(): void {
    console.log('LoginService - Carregando usuário do localStorage');
    const userData = localStorage.getItem('user');
    if (userData) {
      try {
        const user = JSON.parse(userData);
        console.log('LoginService - Usuário carregado:', user);
        this.userSubject.next(user);
      } catch (error) {
        console.error('Erro ao parsear usuário do localStorage:', error);
      }
    } else {
      console.log('LoginService - Nenhum usuário no localStorage');
    }
  }
}
