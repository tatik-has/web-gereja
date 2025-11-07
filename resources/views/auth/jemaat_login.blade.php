<form action="{{ route('jemaat.login.post') }}" method="post">
  @csrf
  <h2>Login Jemaat</h2>
  <input name="email" placeholder="Email" />
  <input name="password" placeholder="Password" type="password" />
  <button type="submit">Login</button>
</form>
<p>Jika akun belum aktif, hubungi admin.</p>
