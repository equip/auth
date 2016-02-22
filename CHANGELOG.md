# 1.1.1

* Add `unknownIdentifier()` and `incorrectPassword()` to `InvalidException`, which should have been included in 1.1.0 for use cases that previously used the `CODE_CREDENTIALS_INVALID` constant

# 1.1.0

* Add exception handling for invalid JWT tokens to the Firebase parser
* Modify exception codes to be consistent with HTTP status codes

# 1.0.0

Initial stable release
