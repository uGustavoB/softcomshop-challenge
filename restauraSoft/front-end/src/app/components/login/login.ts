import { CommonModule } from '@angular/common';
import { Component } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { Router } from '@angular/router';
import { MatIconModule } from '@angular/material/icon';
import { LoginService } from '../../services/login/login.service';
import {ToastrService} from 'ngx-toastr';
import {ApiResponse} from '../../services/API/api.service';

@Component({
  selector: 'app-login',
  imports: [
    CommonModule,
    FormsModule,
    MatIconModule
  ],
  templateUrl: './login.html'
})
export class Login {
  isLoginMode = true;
  isLoading = false;
  error = '';

  email = '';
  password = '';
  name = '';
  confirmPassword = '';

  constructor(
    private auth: LoginService,
    private router: Router,
    private toastr: ToastrService
  ) {}

  toggleMode(): void {
    this.isLoginMode = !this.isLoginMode;
    this.error = '';
    this.resetForm();
  }

  resetForm(): void {
    this.email = '';
    this.password = '';
    this.name = '';
    this.confirmPassword = '';
    this.error = '';
  }

  validateForm(): boolean {
    this.error = '';

    if (!this.email.trim()) {
      this.error = 'Email é obrigatório';
      return false;
    }

    if (!this.password.trim()) {
      this.error = 'Senha é obrigatória';
      return false;
    }

    if (!this.isLoginMode) {
      if (!this.name.trim()) {
        this.error = 'Nome é obrigatório';
        return false;
      }

      if (this.password !== this.confirmPassword) {
        this.error = 'As senhas não coincidem';
        return false;
      }

      if (this.password.length < 6) {
        this.error = 'A senha deve ter pelo menos 6 caracteres';
        return false;
      }
    }

    return true;
  }

  onSubmit(): void {
    if (!this.validateForm()) {
      return;
    }

    this.isLoading = true;
    this.error = '';

    if (this.isLoginMode) {
      this.handleLogin();
    } else {
      this.handleRegister();
    }
  }

  private handleLogin(): void {
    this.auth.login(this.email, this.password).subscribe({
      next: (response: ApiResponse<any>) => {
        console.log('LoginComponent - Login bem-sucedido:', response);

        if (response.status === 'success') {
          this.toastr.success('Login realizado com sucesso!', 'Bem-vindo');
          this.router.navigate(['/pedidos']);
        } else {
          this.error = response.message || 'Erro no login';
          this.toastr.error(this.error, 'Erro');
        }
      },
      error: (error) => {
        console.error('LoginComponent - Erro no login:', error);

        if (!error.error?.message) {
          this.error = 'Email ou senha incorretos';
        }
        this.isLoading = false;
      },
      complete: () => {
        this.isLoading = false;
      }
    });
  }

  private handleRegister(): void {
    console.log('LoginComponent - Iniciando registro...');

    this.auth.register(this.name, this.email, this.password).subscribe({
      next: (response: ApiResponse<any>) => {
        if (response.status === 'success') {
          this.toastr.success('Cadastro realizado com sucesso!', 'Bem-vindo');
          this.router.navigate(['/pedidos']);
        } else {
          this.error = response.message || 'Erro no registro';
          this.toastr.error(this.error, 'Erro');
        }
      },
      error: (error) => {
        console.error('LoginComponent - Erro no registro:', error);
        this.isLoading = false;
      },
      complete: () => {
        this.isLoading = false;
      }
    });
  }
}
