<form action="{{ route('admin.login.post') }}" method="post">
  @csrf
  <h2>Login Admin</h2>
  <input name="email" placeholder="Email" />
  <input name="password" placeholder="Password" type="password" />
  <button type="submit">Login</button>
</form>
