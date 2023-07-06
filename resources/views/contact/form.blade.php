<form action="/contact/submit" method="POST">
    <div id="name-group" class="form-group">
      <label for="name">Name</label>
      <input
        type="text"
        class="form-control"
        id="name"
        name="name"
        placeholder="Full Name"
      />
      <div class="error-block" style="display: none;"></div>
    </div>

    <div id="email-group" class="form-group">
      <label for="email">Email</label>
      <input
        type="text"
        class="form-control"
        id="email"
        name="email"
        placeholder="email@example.com"
      />
      <div class="error-block" style="display: none;"></div>
    </div>

    <div id="superhero-group" class="form-group">
      <label for="superheroAlias">Superhero Alias</label>
      <input
        type="text"
        class="form-control"
        id="superheroAlias"
        name="superheroAlias"
        placeholder="Ant Man, Wonder Woman, Black Panther, Superman, Black Widow"
      />
      <div class="error-block" style="display: none;"></div>
    </div>

    <button type="submit" class="btn btn-success">
      Submit
    </button>

    <div class="alert alert-success" style="display: none;"></div>
</form>