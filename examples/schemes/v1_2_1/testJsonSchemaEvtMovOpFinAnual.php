<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../../bootstrap.php';

use JsonSchema\Constraints\Constraint;
use JsonSchema\Constraints\Factory;
use JsonSchema\SchemaStorage;
use JsonSchema\Validator;

$evento = 'evtMovOpFinAnual';
$version = '1_2_1';

$jsonSchema = '{
    "title": "evtMovOpFinAnual",
    "type": "object",
    "properties": {
        "sequencial": {
            "required": true,
            "type": "string",
            "pattern": "^[0-9]{1,18}"
        },
        "indretificacao": {
            "required": true,
            "type": "integer",
            "minimum": 1,
            "maximum": 3
        },
        "nrrecibo": {
            "required": false,
            "type": ["string","null"],
            "pattern": "^([0-9]{1,18}[-][0-9]{2}[-][0-9]{3}[-][0-9]{4}[-][0-9]{1,18})$"
        },
        "anocaixa": {
            "required": true,
            "type": "string",
            "pattern": "^20([0-9][0-9])"
        },
        "semestre": {
            "required": true,
            "type": "integer",
            "minimum": 1,
            "maximum": 2
        },
        "tpni": {
            "required": true,
            "type": "integer",
            "minimum": 1,
            "maximum": 99
        },
        "tpdeclarado": {
            "required": false,
            "type": ["string","null"],
            "minLength": 1,
            "maxLength": 8
        },
        "nideclarado": {
            "required": true,
            "type": "string",
            "minLength": 1,
            "maxLength": 25
        },
        "nomedeclarado": {
            "required": true,
            "type": "string",
            "minLength": 1,
            "maxLength": 100
        },
        "tpnomedeclarado": {
            "required": false,
            "type": ["string","null"],
            "minLength": 1,
            "maxLength": 7
        },
        "enderecolivre": {
            "required": false,
            "type": ["string","null"],
            "minLength": 1,
            "maxLength": 200
        },
        "tpendereco": {
            "required": false,
            "type": ["string","null"],
            "minLength": 1,
            "maxLength": 7
        },
        "pais": {
            "required": true,
            "type": "string",
            "minLength": 1,
            "maxLength": 2
        },
        "datanasc": {
            "required": false,
            "type": ["string","null"],
            "pattern": "^(19[0-9][0-9]|2[0-9][0-9][0-9])[-/](0?[1-9]|1[0-2])[-/](0?[1-9]|[12][0-9]|3[01])$"
        },
        "nif": {
            "required": false,
            "type": ["array","null"],
            "minItems": 0,
            "items": {
                "type": "object",
                "properties": {
                    "numeronif": {
                        "required": true,
                        "type": "string",
                        "minLength": 1,
                        "maxLength": 25
                    },
                    "paisemissaonif": {
                        "required": true,
                        "type": "string",
                        "minLength": 2,
                        "maxLength": 2
                    },
                    "tpnif": {
                        "required": false,
                        "type": ["string","null"],
                        "minLength": 1,
                        "maxLength": 30
                    }
                }
            }    
        },
        "nomeoutros": {
            "required": false,
            "type": ["array","null"],
            "minItems": 0,
            "items": {
                "type": "object",
                "properties": {
                    "nomepf": {
                        "required": false,
                        "type": ["object","null"],
                        "properties": {
                            "tpnome": {
                                "required": false,
                                "type": ["string","null"],
                                "minLength": 1,
                                "maxLength": 7
                            },
                            "prectitulo": {
                                "required": false,
                                "type": ["string","null"],
                                "minLength": 1,
                                "maxLength": 20
                            },
                            "titulo": {
                                "required": false,
                                "type": ["string","null"],
                                "minLength": 1,
                                "maxLength": 20
                            },
                            "idgeracao": {
                                "required": false,
                                "type": ["string","null"],
                                "minLength": 1,
                                "maxLength": 10
                            },
                            "sufixo": {
                                "required": false,
                                "type": ["string","null"],
                                "minLength": 1,
                                "maxLength": 10
                            },
                            "gensufixo": {
                                "required": false,
                                "type": ["string","null"],
                                "minLength": 1,
                                "maxLength": 10
                            },
                            "primeironome": {
                                "required": true,
                                "type": "object",
                                "properties": {
                                    "tipo": {
                                        "required": false,
                                        "type": ["string","null"],
                                        "minLength": 1,
                                        "maxLength": 20
                                    },
                                    "nome": {
                                        "required": true,
                                        "type": "string",
                                        "minLength": 1,
                                        "maxLength": 20
                                    }
                                }
                            },
                            "meionome": {
                                "required": false,
                                "type": ["array","null"],
                                "minItems": 0,
                                "items": {
                                    "type": "object",
                                    "properties": {
                                        "tipo": {
                                            "required": false,
                                            "type": ["string","null"],
                                            "minLength": 1,
                                            "maxLength": 20
                                        },
                                        "nome": {
                                            "required": true,
                                            "type": "string",
                                            "minLength": 1,
                                            "maxLength": 20
                                        }
                                    }
                                }    
                            },
                            "prefixonome": {
                                "required": false,
                                "type": ["object","null"],
                                "properties": {
                                    "tipo": {
                                        "required": false,
                                        "type": ["string","null"],
                                        "minLength": 1,
                                        "maxLength": 20
                                    },
                                    "nome": {
                                        "required": true,
                                        "type": "string",
                                        "minLength": 1,
                                        "maxLength": 10
                                    }
                                }
                            },
                            "ultimonome": {
                                "required": true,
                                "type": "object",
                                "properties": {
                                    "tipo": {
                                        "required": false,
                                        "type": ["string","null"],
                                        "minLength": 1,
                                        "maxLength": 20
                                    },
                                    "nome": {
                                        "required": true,
                                        "type": "string",
                                        "minLength": 1,
                                        "maxLength": 20
                                    }
                                }
                            }
                        }
                    },    
                    "nomepj": {
                        "required": false,
                        "type": ["object","null"],
                        "properties": {
                            "tipo": {
                                "required": false,
                                "type": ["string","null"],
                                "minLength": 1,
                                "maxLength": 7
                            },
                            "nome": {
                                "required": true,
                                "type": "string",
                                "minLength": 1,
                                "maxLength": 100
                            }
                        }
                    },
                    "infonascimento": {
                        "required": false,
                        "type": ["object","null"],
                        "properties": {
                            "municipio": {
                                "required": false,
                                "type": ["string","null"],
                                "minLength": 1,
                                "maxLength": 60
                            },
                            "bairro": {
                                "required": false,
                                "type": ["string","null"],
                                "minLength": 1,
                                "maxLength": 40
                            },
                            "pais": {
                                "required": false,
                                "type": ["string","null"],
                                "minLength": 2,
                                "maxLength": 2
                            },
                            "antigonomepais": {
                                "required": false,
                                "type": ["string","null"],
                                "minLength": 1,
                                "maxLength": 40
                            }
                        }
                    }                
                }
            }    
        },
        "enderecooutros": {
            "required": false,
            "type": ["array","null"],
            "minItems": 0,
            "items": {
                "type": "object",
                "properties": {
                    "tpendereco": {
                        "required": false,
                        "type": ["string","null"],
                        "minLength": 1,
                        "maxLength": 7
                    },
                    "enderecolivre": {
                        "required": false,
                        "type": ["string","null"],
                        "minLength": 1,
                        "maxLength": 200
                    },
                    "enderecoestrutura": {
                        "required": false,
                        "type": ["object","null"],
                        "properties": {
                            "enderecolivre": {
                                "required": false,
                                "type": ["string","null"],
                                "minLength": 1,
                                "maxLength": 200
                            },
                            "cep": {
                                "required": true,
                                "type": "string",
                                "minLength": 1,
                                "maxLength": 12
                            },
                            "municipio": {
                                "required": true,
                                "type": "string",
                                "minLength": 1,
                                "maxLength": 60
                            },
                            "uf": {
                                "required": true,
                                "type": "string",
                                "minLength": 1,
                                "maxLength": 40
                            },
                            "pais": {
                                "required": true,
                                "type": "string",
                                "minLength": 2,
                                "maxLength": 2
                            },
                            "endereco": {
                                "required": false,
                                "type": ["object","null"],
                                "properties": {
                                    "logradouro": {
                                        "required": false,
                                        "type": ["string","null"],
                                        "minLength": 1,
                                        "maxLength": 60
                                    },
                                    "numero": {
                                        "required": false,
                                        "type": ["string","null"],
                                        "minLength": 1,
                                        "maxLength": 10
                                    },
                                    "complemento": {
                                        "required": false,
                                        "type": ["string","null"],
                                        "minLength": 1,
                                        "maxLength": 10
                                    },
                                    "andar": {
                                        "required": false,
                                        "type": ["string","null"],
                                        "minLength": 1,
                                        "maxLength": 10
                                    },
                                    "bairro": {
                                        "required": false,
                                        "type": ["string","null"],
                                        "minLength": 1,
                                        "maxLength": 40
                                    },
                                    "caixapostal": {
                                        "required": false,
                                        "type": ["string","null"],
                                        "minLength": 1,
                                        "maxLength": 12
                                    }
                                }
                            }
                        }
                    }    
                }
            }
        },
        "paisresid": {
            "required": false,
            "type": ["array","null"],
            "minItems": 0,
            "items": {
                "type": "object",
                "properties": {
                    "pais": {
                        "required": true,
                        "type": "string",
                        "minLength": 2,
                        "maxLength": 2
                    }
                }
            }    
        },
        "paisnacionalidade": {
            "required": false,
            "type": ["array","null"],
            "minItems": 0,
            "items": {
                "type": "object",
                "properties": {
                    "pais": {
                        "required": true,
                        "type": "string",
                        "minLength": 2,
                        "maxLength": 2
                    }
                }
            }    
        },
        "proprietarios": {
            "required": false,
            "type": ["array","null"],
            "minItems": 0,
            "items": {
                "type": "object",
                "properties": {
                    "tpni": {
                        "required": true,
                        "type": "integer",
                        "minLength": 1,
                        "maxLength": 5
                    },
                    "niproprietario": {
                        "required": true,
                        "type": "string",
                        "minLength": 1,
                        "maxLength": 25
                    },
                    "tpproprietario": {
                        "required": false,
                        "type": ["string","null"],
                        "minLength": 1,
                        "maxLength": 8
                    },
                    "nome": {
                        "required": true,
                        "type": "string",
                        "minLength": 1,
                        "maxLength": 100
                    },
                    "tpnome": {
                        "required": false,
                        "type": ["string","null"],
                        "minLength": 1,
                        "maxLength": 7
                    },
                    "enderecolivre": {
                        "required": true,
                        "type": "string",
                        "minLength": 1,
                        "maxLength": 200
                    },
                    "tpendereco": {
                        "required": false,
                        "type": ["string","null"],
                        "minLength": 1,
                        "maxLength": 7
                    },
                    "pais": {
                        "required": true,
                        "type": "string",
                        "minLength": 2,
                        "maxLength": 2
                    },
                    "datanasc": {
                        "required": false,
                        "type": ["string","null"],
                        "pattern": "^(19[0-9][0-9]|2[0-9][0-9][0-9])[-/](0?[1-9]|1[0-2])[-/](0?[1-9]|[12][0-9]|3[01])$"
                    },
                    "nif": {
                        "required": false,
                        "type": ["array","null"],
                        "minItems": 0,
                        "items": {
                            "type": "object",
                            "properties": {
                                "numeronif": {
                                    "required": true,
                                    "type": "string",
                                    "minLength": 1,
                                    "maxLength": 25
                                },
                                "paisemissaonif": {
                                    "required": true,
                                    "type": "string",
                                    "minLength": 2,
                                    "maxLength": 2
                                }
                            }
                        }    
                    },
                    "nomeoutros": {
                        "required": false,
                        "type": ["array","null"],
                        "minItems": 0,
                        "items": {
                            "type": "object",
                            "properties": {
                                "nomepf": {
                                    "required": false,
                                    "type": ["object","null"],
                                    "properties": {
                                        "tpnome": {
                                            "required": false,
                                            "type": ["string","null"],
                                            "minLength": 1,
                                            "maxLength": 7
                                        },
                                        "prectitulo": {
                                            "required": false,
                                            "type": ["string","null"],
                                            "minLength": 1,
                                            "maxLength": 20
                                        },
                                        "titulo": {
                                            "required": false,
                                            "type": ["string","null"],
                                            "minLength": 1,
                                            "maxLength": 10
                                        },
                                        "idgeracao": {
                                            "required": false,
                                            "type": ["string","null"],
                                            "minLength": 1,
                                            "maxLength": 10
                                        },
                                        "sufixo": {
                                            "required": false,
                                            "type": ["string","null"],
                                            "minLength": 1,
                                            "maxLength": 10
                                        },
                                        "gensufixo": {
                                            "required": false,
                                            "type": ["string","null"],
                                            "minLength": 1,
                                            "maxLength": 10
                                        },
                                        "primeironome": {
                                            "required": true,
                                            "type": "object",
                                            "properties": {
                                                "tipo": {
                                                    "required": false,
                                                    "type": ["string","null"],
                                                    "minLength": 1,
                                                    "maxLength": 20
                                                },
                                                "nome": {
                                                    "required": true,
                                                    "type": "string",
                                                    "minLength": 1,
                                                    "maxLength": 20
                                                }
                                            }
                                        },
                                        "meionome": {
                                            "required": false,
                                            "type": ["array","null"],
                                            "minItems": 0,
                                            "items": {
                                                "type": "object",
                                                "properties": {
                                                    "tipo": {
                                                        "required": false,
                                                        "type": ["string","null"],
                                                        "minLength": 1,
                                                        "maxLength": 20
                                                    },
                                                    "nome": {
                                                        "required": true,
                                                        "type": "string",
                                                        "minLength": 1,
                                                        "maxLength": 20
                                                    }
                                                }
                                            }    
                                        },
                                        "prefixonome": {
                                            "required": false,
                                            "type": ["object","null"],
                                            "properties": {
                                                "tipo": {
                                                    "required": false,
                                                    "type": ["string","null"],
                                                    "minLength": 1,
                                                    "maxLength": 20
                                                },
                                                "nome": {
                                                    "required": true,
                                                    "type": "string",
                                                    "minLength": 1,
                                                    "maxLength": 10
                                                }
                                            }
                                        },
                                        "ultimonome": {
                                            "required": true,
                                            "type": "object",
                                            "properties": {
                                                "tipo": {
                                                    "required": false,
                                                    "type": ["string","null"],
                                                    "minLength": 1,
                                                    "maxLength": 20
                                                },
                                                "nome": {
                                                    "required": true,
                                                    "type": "string",
                                                    "minLength": 1,
                                                    "maxLength": 20
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }    
                    },
                    "enderecooutros": {
                        "required": false,
                        "type": ["array","null"],
                        "minItems": 0,
                        "items": {
                            "type": "object",
                            "properties": {
                                "tpendereco": {
                                    "required": false,
                                    "type": ["string","null"],
                                    "minLength": 1,
                                    "maxLength": 7
                                },
                                "enderecolivre": {
                                    "required": false,
                                    "type": ["string","null"],
                                    "minLength": 3,
                                    "maxLength": 200
                                },
                                "pais": {
                                    "required": true,
                                    "type": "string",
                                    "minLength": 2,
                                    "maxLength": 2
                                },
                                "enderecoestrutura": {
                                    "required": false,
                                    "type": ["object","null"],
                                    "properties": {
                                        "enderecolivre": {
                                            "required": false,
                                            "type": ["string","null"],
                                            "minLength": 3,
                                            "maxLength": 200
                                        },
                                        "cep": {
                                            "required": true,
                                            "type": "string",
                                            "minLength": 1,
                                            "maxLength": 12
                                        },
                                        "municipio": {
                                            "required": true,
                                            "type": "string",
                                            "minLength": 1,
                                            "maxLength": 60
                                        },
                                        "uf": {
                                            "required": true,
                                            "type": "string",
                                            "minLength": 1,
                                            "maxLength": 40
                                        },
                                        "endereco": {
                                            "required": false,
                                            "type": ["object","null"],
                                            "properties": {
                                                "logradouro": {
                                                    "required": false,
                                                    "type": ["string","null"],
                                                    "minLength": 1,
                                                    "maxLength": 60
                                                },
                                                "numero": {
                                                    "required": false,
                                                    "type": ["string","null"],
                                                    "minLength": 1,
                                                    "maxLength": 10
                                                },
                                                "complemento": {
                                                    "required": false,
                                                    "type": ["string","null"],
                                                    "minLength": 1,
                                                    "maxLength": 10
                                                },
                                                "andar": {
                                                    "required": false,
                                                    "type": ["string","null"],
                                                    "minLength": 1,
                                                    "maxLength": 10
                                                },
                                                "bairro": {
                                                    "required": false,
                                                    "type": ["string","null"],
                                                    "minLength": 1,
                                                    "maxLength": 40
                                                },
                                                "caixapostal": {
                                                    "required": false,
                                                    "type": ["string","null"],
                                                    "minLength": 1,
                                                    "maxLength": 12
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }    
                    },
                    "paisresid": {
                        "required": false,
                        "type": ["array","null"],
                        "minItems": 0,
                        "items": {
                            "type": "object",
                            "properties": {
                                "pais": {
                                    "required": true,
                                    "type": "string",
                                    "minLength": 2,
                                    "maxLength": 2
                                }
                            }
                        }    
                    },
                    "paisnacionalidade": {
                        "required": false,
                        "type": ["array","null"],
                        "minItems": 0,
                        "items": {
                            "type": "object",
                            "properties": {
                                "pais": {
                                    "required": true,
                                    "type": "string",
                                    "minLength": 2,
                                    "maxLength": 2
                                }
                            }
                        }    
                    },
                    "infonascimento": {
                        "required": false,
                        "type": ["object","null"],
                        "properties": {
                            "municipio": {
                                "required": false,
                                "type": ["string","null"],
                                "minLength": 2,
                                "maxLength": 60
                            },
                            "bairro": {
                                "required": false,
                                "type": ["string","null"],
                                "minLength": 2,
                                "maxLength": 40
                            },
                            "pais": {
                                "required": false,
                                "type": ["string","null"],
                                "minLength": 2,
                                "maxLength": 2
                            },
                            "antigonomepais": {
                                "required": false,
                                "type": ["string","null"],
                                "minLength": 2,
                                "maxLength": 40
                            }
                        }
                    },
                    "reportavel": {
                        "required": true,
                        "type": "array",
                        "minItems": 1,
                        "items": {
                            "type": "object",
                            "properties": {
                                "pais": {
                                    "required": true,
                                    "type": "string",
                                    "minLength": 2,
                                    "maxLength": 2
                                }
                            }
                        }    
                    }
                }
            }    
        },
        "anomescaixa": {
            "required": true,
            "type": "string",
            "pattern": "^[0-9]{6}"
        },
        "conta": {
            "required": false,
            "type": ["array","null"],
            "minItems": 0,
            "items": {
                "type": "object",
                "properties": {
                    "medjudic": {
                        "required": false,
                        "type": ["array","null"],
                        "minItems": 0,
                        "items": {
                            "type": "object",
                            "properties": {
                                "numprocjud": {
                                    "required": true,
                                    "type": "string",
                                    "pattern": "^[0-9]{1,21}"
                                },
                                "vara": {
                                    "required": true,
                                    "type": "integer",
                                    "minimum": 1,
                                    "maximum": 99
                                },
                                "secjud": {
                                    "required": true,
                                    "type": "integer",
                                    "minimum": 1,
                                    "maximum": 99
                                },
                                "subsecjud": {
                                    "required": true,
                                    "type": "string",
                                    "minLength": 1,
                                    "maxLength": 40
                                },
                                "dtconcessao": {
                                    "required": true,
                                    "type": "string",
                                    "pattern": "^(19[0-9][0-9]|2[0-9][0-9][0-9])[-/](0?[1-9]|1[0-2])[-/](0?[1-9]|[12][0-9]|3[01])$"
                                },
                                "dtcassacao": {
                                    "required": false,
                                    "type": ["string","null"],
                                    "pattern": "^(19[0-9][0-9]|2[0-9][0-9][0-9])[-/](0?[1-9]|1[0-2])[-/](0?[1-9]|[12][0-9]|3[01])$"
                                }
                            }
                        }    
                    },
                    "infoconta": {
                        "required": false,
                        "type": ["object","null"],
                        "properties": {
                            "tpconta": {
                                "required": true,
                                "type": "string",
                                "minLength": 1,
                                "maxLength": 1
                            },
                            "subtpconta": {
                                "required": true,
                                "type": "string",
                                "minLength": 1,
                                "maxLength": 3
                            },
                            "tpnumconta": {
                                "required": true,
                                "type": "string",
                                "minLength": 1,
                                "maxLength": 10
                            },
                            "numconta": {
                                "required": true,
                                "type": "string",
                                "minLength": 1,
                                "maxLength": 50
                            },
                            "tprelacaodeclarado": {
                                "required": true,
                                "type": "integer",
                                "minLength": 1,
                                "maxLength": 5
                            },
                            "notitulares": {
                                "required": false,
                                "type": ["integer","null"],
                                "minimum": 1,
                                "maximum": 99
                            },
                            "dtencerramentoconta": {
                                "required": false,
                                "type": ["string","null"],
                                "pattern": "^(19[0-9][0-9]|2[0-9][0-9][0-9])[-/](0?[1-9]|1[0-2])[-/](0?[1-9]|[12][0-9]|3[01])$"
                            },
                            "indinatividade": {
                                "required": false,
                                "type": ["integer","null"],
                                "minimum": 1,
                                "maximum": 1
                            },
                            "indndoc": {
                                "required": false,
                                "type": ["integer","null"],
                                "minimum": 1,
                                "maximum": 1
                            },
                            "vlrultdia": {
                                "required": false,
                                "type": ["number","null"]
                            },
                            "reportavel": {
                                "required": true,
                                "type": "array",
                                "minItems": 1,
                                "items": {
                                    "type": "object",
                                    "properties": {
                                        "pais": {
                                            "required": true,
                                            "type": "string",
                                            "minLength": 2,
                                            "maxLength": 2
                                        }
                                    }
                                }
                            },
                            "intermediario": {
                                "required": false,
                                "type": ["object","null"],
                                "properties": {
                                    "giin": {
                                        "required": false,
                                        "type": ["string","null"],
                                        "pattern": "^([0-9A-NP-Z]{6}[.][0-9A-NP-Z]{5}[.](LE|SL|ME|BR|SF|SD|SS|SB|SP)[.][0-9]{3})$"
                                    },
                                    "tpni": {
                                        "required": true,
                                        "type": "integer",
                                        "minimum": 1,
                                        "maximum": 5
                                    },
                                    "niintermediario": {
                                        "required": false,
                                        "type": ["string","null"],
                                        "minLength": 1,
                                        "maxLength": 25
                                    }
                                }
                            },
                            "fundo": {
                                "required": false,
                                "type": ["object","null"],
                                "properties": {
                                    "giin": {
                                        "required": false,
                                        "type": ["string","null"],
                                        "pattern": "^([0-9A-NP-Z]{6}[.][0-9A-NP-Z]{5}[.](LE|SL|ME|BR|SF|SD|SS|SB|SP)[.][0-9]{3})$"
                                    },
                                    "cnpj": {
                                        "required": true,
                                        "type": "string",
                                        "pattern": "^[0-9]{14}"
                                    }
                                }
                            },
                            "pgtosacum": {
                                "required": true,
                                "type": "array",
                                "minItems": 1,
                                "items": {
                                    "type": "object",
                                    "properties": {
                                        "tppgto": {
                                            "required": true,
                                            "type": "string",
                                            "minLength": 1,
                                            "maxLength": 10
                                        },
                                        "totpgtosacum": {
                                            "required": true,
                                            "type": "number"
                                        }
                                    }
                                }    
                            }
                        }
                    }
                }
            }    
        }
    }
}';

$std = new \stdClass();
$std->sequencial = '1';
$std->indretificacao = 3;
$std->nrrecibo = '123456789012345678-12-123-1234-123456789012345678';
$std->anocaixa = '2017';
$std->semestre = 2;
$std->tpni = 2;
$std->tpdeclarado = 'klsks';
$std->nideclarado = 'ssss';

$std->nomedeclarado = 'slkcskkslsklsklsk';
$std->tpnomedeclarado = 'slsklsk';

$std->enderecolivre = 'ssklsklskslks';
$std->tpendereco = 'ssk';
$std->pais = 'BR';
$std->datanasc = '2017-01-01';

$std->anomescaixa = '201712';//??

$std->nif[0] = new \stdClass();
$std->nif[0]->numeronif = 'skjskjskjs';
$std->nif[0]->paisemissaonif = 'BR';
$std->nif[0]->tpnif = 'slksl';

$std->nomeoutros[0] = new \stdClass();
$std->nomeoutros[0]->nomepf = new \stdClass();
$std->nomeoutros[0]->nomepf->tpnome = 'slsklsk';
$std->nomeoutros[0]->nomepf->prectitulo = 'sss';
$std->nomeoutros[0]->nomepf->titulo = 'slsklsk';

$std->nomeoutros[0]->nomepf->idgeracao = 'sss';

$std->nomeoutros[0]->nomepf->sufixo = 'sss';
$std->nomeoutros[0]->nomepf->gensufixo = 'sss';

$std->nomeoutros[0]->nomepf->primeironome = new \stdClass();
$std->nomeoutros[0]->nomepf->primeironome->tipo = 'lsklsk';
$std->nomeoutros[0]->nomepf->primeironome->nome = 'lkdlkdlkd';

$std->nomeoutros[0]->nomepf->meionome[0] = new \stdClass();
$std->nomeoutros[0]->nomepf->meionome[0]->tipo = 'lkslk';
$std->nomeoutros[0]->nomepf->meionome[0]->nome = 'flkfk';

$std->nomeoutros[0]->nomepf->prefixonome = new \stdClass();
$std->nomeoutros[0]->nomepf->prefixonome->tipo = 'dldkk';
$std->nomeoutros[0]->nomepf->prefixonome->nome = 'flklf';

$std->nomeoutros[0]->nomepf->ultimonome = new \stdClass();
$std->nomeoutros[0]->nomepf->ultimonome->tipo = 'dddlk';
$std->nomeoutros[0]->nomepf->ultimonome->nome = 'flfkflkf';

$std->nomeoutros[0]->nomepj = new \stdClass();
$std->nomeoutros[0]->nomepj->tpnome = 'dkddkld';
$std->nomeoutros[0]->nomepj->nome = 'ddcldcllc';

$std->infonascimento = new \stdClass();
$std->infonascimento->municipio = 'dcldcldcl';
$std->infonascimento->bairro = 'fflkflkflk';
$std->infonascimento->pais = 'RF';
$std->infonascimento->antigonomepais = 'flkflkfl';

$std->enderecooutros[0] = new \stdClass();
$std->enderecooutros[0]->tpendereco = 'ddlcdld';
$std->enderecooutros[0]->enderecolivre = 'kjdkdj';
$std->enderecooutros[0]->pais = 'BR';

$std->enderecooutros[0]->enderecoestrutura = new \stdClass();
$std->enderecooutros[0]->enderecoestrutura->enderecolivre = 'skslkslks';
$std->enderecooutros[0]->enderecoestrutura->cep = '12345678';
$std->enderecooutros[0]->enderecoestrutura->municipio = 'slkslksl';
$std->enderecooutros[0]->enderecoestrutura->uf = 'slkslskslk';
$std->enderecooutros[0]->enderecoestrutura->pais = 'ss';

$std->enderecooutros[0]->enderecoestrutura->endereco = new \stdClass();
$std->enderecooutros[0]->enderecoestrutura->endereco->logradouro = 'ssjhskhsjsj';
$std->enderecooutros[0]->enderecoestrutura->endereco->numero = 'sss';
$std->enderecooutros[0]->enderecoestrutura->endereco->complemento = 'ssjsjh';
$std->enderecooutros[0]->enderecoestrutura->endereco->andar = 'sss';
$std->enderecooutros[0]->enderecoestrutura->endereco->bairro = 'ssjhsjhsjhs';
$std->enderecooutros[0]->enderecoestrutura->endereco->caixapostal = 'sskjskj';

$std->paisresid[0] = new \stdClass();
$std->paisresid[0]->pais = 'BR';

$std->paisnacionalidade[0] = new \stdClass();
$std->paisnacionalidade[0]->pais = 'BR';

$std->proprietarios[0] = new \stdClass();
$std->proprietarios[0]->tpni = 1;
$std->proprietarios[0]->niproprietario = 'ssssss';
$std->proprietarios[0]->tpproprietario = 'sslks';
$std->proprietarios[0]->nome = 'skjsksjksj';
$std->proprietarios[0]->tpnome = 'sss';
$std->proprietarios[0]->enderecolivre = 'ssjhsjhsjh';
$std->proprietarios[0]->tpendereco = 'skjsksj';
$std->proprietarios[0]->pais = 'BR';
$std->proprietarios[0]->datanasc = '2017-01-01';

$std->proprietarios[0]->nif[0] = new \stdClass();
$std->proprietarios[0]->nif[0]->numeronif = '1233';
$std->proprietarios[0]->nif[0]->paisemissaonif = 'BR';

$std->proprietarios[0]->nomeoutros[0] = new \stdClass();
$std->proprietarios[0]->nomeoutros[0]->nomepf = new \stdClass();
$std->proprietarios[0]->nomeoutros[0]->nomepf->tpnome = 'ksksk';
$std->proprietarios[0]->nomeoutros[0]->nomepf->prectitulo = 'iuwiuw';
$std->proprietarios[0]->nomeoutros[0]->nomepf->titulo = 'wwkklwk';
$std->proprietarios[0]->nomeoutros[0]->nomepf->idgeracao = 'kkdkd';
$std->proprietarios[0]->nomeoutros[0]->nomepf->sufixo = 'kjdkdk';
$std->proprietarios[0]->nomeoutros[0]->nomepf->gensufixo = 'jdjdjd';

$std->proprietarios[0]->nomeoutros[0]->nomepf->primeironome = new \stdClass();
$std->proprietarios[0]->nomeoutros[0]->nomepf->primeironome->tipo = 'wwwkw';
$std->proprietarios[0]->nomeoutros[0]->nomepf->primeironome->nome = 'lwkwlkw';

$std->proprietarios[0]->nomeoutros[0]->nomepf->meionome[0] = new \stdClass();
$std->proprietarios[0]->nomeoutros[0]->nomepf->meionome[0]->tipo = 'iwiwiw';
$std->proprietarios[0]->nomeoutros[0]->nomepf->meionome[0]->nome = 'slksksskks';

$std->proprietarios[0]->nomeoutros[0]->nomepf->prefixonome = new \stdClass();
$std->proprietarios[0]->nomeoutros[0]->nomepf->prefixonome->tipo = 'ssss';
$std->proprietarios[0]->nomeoutros[0]->nomepf->prefixonome->nome = 'sksksksk';

$std->proprietarios[0]->nomeoutros[0]->nomepf->ultimonome = new \stdClass();
$std->proprietarios[0]->nomeoutros[0]->nomepf->ultimonome->tipo = 'ssss';
$std->proprietarios[0]->nomeoutros[0]->nomepf->ultimonome->nome = 'ksksksk';

$std->proprietarios[0]->enderecooutros[0] = new \stdClass();
$std->proprietarios[0]->enderecooutros[0]->tpendereco = 'skjskj';
$std->proprietarios[0]->enderecooutros[0]->enderecolivre = 'slklsklsklsk';
$std->proprietarios[0]->enderecooutros[0]->pais = 'BR';

$std->proprietarios[0]->enderecooutros[0]->enderecoestrutura = new \stdClass();
$std->proprietarios[0]->enderecooutros[0]->enderecoestrutura->enderecolivre = 'ljlkjskjslksj';
$std->proprietarios[0]->enderecooutros[0]->enderecoestrutura->cep = '123456789012';
$std->proprietarios[0]->enderecooutros[0]->enderecoestrutura->municipio = 'sljslkjsksj';
$std->proprietarios[0]->enderecooutros[0]->enderecoestrutura->uf = 'kjskjsksj';

$std->proprietarios[0]->enderecooutros[0]->enderecoestrutura->endereco = new \stdClass();
$std->proprietarios[0]->enderecooutros[0]->enderecoestrutura->endereco->logradouro = 'kjskjksjskjsk';
$std->proprietarios[0]->enderecooutros[0]->enderecoestrutura->endereco->numero = 'kslksk';
$std->proprietarios[0]->enderecooutros[0]->enderecoestrutura->endereco->complemento = 'uiui';
$std->proprietarios[0]->enderecooutros[0]->enderecoestrutura->endereco->andar = 'ssss';
$std->proprietarios[0]->enderecooutros[0]->enderecoestrutura->endereco->bairro = 'sssssssss';
$std->proprietarios[0]->enderecooutros[0]->enderecoestrutura->endereco->caixapostal = 'ieiei';

$std->proprietarios[0]->paisresid[0] = new \stdClass();
$std->proprietarios[0]->paisresid[0]->pais = 'BR';

$std->proprietarios[0]->paisnacionalidade[0] = new \stdClass();
$std->proprietarios[0]->paisnacionalidade[0]->pais = 'BR';

$std->proprietarios[0]->infonascimento = new \stdClass();
$std->proprietarios[0]->infonascimento->municipio = 'lkslklsk';
$std->proprietarios[0]->infonascimento->bairro = 'klksks';
$std->proprietarios[0]->infonascimento->pais = 'BR';
$std->proprietarios[0]->infonascimento->antigonomepais = 'kjskjskj';

$std->proprietarios[0]->reportavel[0] = new \stdClass();
$std->proprietarios[0]->reportavel[0]->pais = 'BR';


$std->anocaixa = '2017';
$std->semestre = 2; //1 ou 2
$std->conta[0] = new \stdClass();

$std->conta[0]->medjudic[0] = new \stdClass();
$std->conta[0]->medjudic[0]->numprocjud = '122121211';
$std->conta[0]->medjudic[0]->vara = 33;
$std->conta[0]->medjudic[0]->secjud = 12;
$std->conta[0]->medjudic[0]->subsecjud = 'sklskslk';
$std->conta[0]->medjudic[0]->dtconcessao = '2016-10-10';
$std->conta[0]->medjudic[0]->dtcassacao = '2017-12-05';

$std->conta[0]->infoconta = new \stdClass();
$std->conta[0]->infoconta->tpconta = 'A';
$std->conta[0]->infoconta->subtpconta = 'asa';
$std->conta[0]->infoconta->tpnumconta = 'assss';
$std->conta[0]->infoconta->numconta = 'aasssdddd';
$std->conta[0]->infoconta->tprelacaodeclarado = 1;
$std->conta[0]->infoconta->notitulares = 5;
$std->conta[0]->infoconta->dtencerramentoconta = '2017-12-12';
$std->conta[0]->infoconta->indinatividade = 1;
$std->conta[0]->infoconta->indndoc = 1;
$std->conta[0]->infoconta->vlrultdia = 700.00;

$std->conta[0]->infoconta->reportavel[0] = new \stdClass();
$std->conta[0]->infoconta->reportavel[0]->pais = 'BR';

$std->conta[0]->infoconta->intermediario = new \stdClass();
$std->conta[0]->infoconta->intermediario->giin = '12ASDA.12345.LE.123';
$std->conta[0]->infoconta->intermediario->tpni = 1;
$std->conta[0]->infoconta->intermediario->niintermediario = 'lslsksklsk';

$std->conta[0]->infoconta->fundo = new \stdClass();
$std->conta[0]->infoconta->fundo->giin = '12ASDA.12345.LE.123';
$std->conta[0]->infoconta->fundo->cnpj = '12345678901234';

$std->conta[0]->infoconta->pgtosacum[0] = new \stdClass();
$std->conta[0]->infoconta->pgtosacum[0]->tppgto = 'ksksksk';
$std->conta[0]->infoconta->pgtosacum[0]->totpgtosacum = 154568978.99;


// Schema must be decoded before it can be used for validation
$jsonSchemaObject = json_decode($jsonSchema);
if (empty($jsonSchemaObject)) {
    echo "<h2>Erro de digitacão no schema ! Revise</h2>";
    echo "<pre>";
    print_r($jsonSchema);
    echo "</pre>";
    die();
}
// The SchemaStorage can resolve references, loading additional schemas from file as needed, etc.
$schemaStorage = new SchemaStorage();

// This does two things:
// 1) Mutates $jsonSchemaObject to normalize the references (to file://mySchema#/definitions/integerData, etc)
// 2) Tells $schemaStorage that references to file://mySchema... should be resolved by looking in $jsonSchemaObject
$schemaStorage->addSchema('file://mySchema', $jsonSchemaObject);

// Provide $schemaStorage to the Validator so that references can be resolved during validation
$jsonValidator = new Validator(new Factory($schemaStorage));

// Do validation (use isValid() and getErrors() to check the result)
$jsonValidator->validate(
    $std,
    $jsonSchemaObject
);

if ($jsonValidator->isValid()) {
    echo "The supplied JSON validates against the schema.<br/>";
} else {
    echo "JSON does not validate. Violations:<br/>";
    foreach ($jsonValidator->getErrors() as $error) {
        echo sprintf("[%s] %s<br/>", $error['property'], $error['message']);
    }
    die;
}
//salva se sucesso
file_put_contents("../../../jsonSchemes/v$version/$evento.schema", $jsonSchema);
